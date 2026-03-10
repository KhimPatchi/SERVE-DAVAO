<?php

namespace App\Services;

use App\Models\User;
use App\Models\Event;
use App\Models\EventVolunteer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ContentBasedFilteringService
{
    /**
     * Recommend events for a specific volunteer
     * Optimized with caching
     */
    public function recommendEventsForVolunteer($volunteerId, $limit = 10)
    {
        // Always fetch fresh from DB to ensure latest saved preferences are used
        $volunteer = User::find($volunteerId);
        if (!$volunteer) {
            return collect();
        }
        // Force fresh attributes (avoid stale session model)
        $volunteer->refresh();

        // Stage 2: Build volunteer's feature vector (Soft Constraints)
        $volunteerFeatures = $this->buildUserFeatures($volunteer);

        if (empty($volunteerFeatures)) {
            // No preferences set — do not show recommendations at all
            return collect();
        }

        // Exclude events already joined/upcoming
        $joinedEventIds = EventVolunteer::where('volunteer_id', $volunteerId)
            ->whereIn('event_volunteers.status', ['registered', 'attended'])
            ->join('events', 'events.id', '=', 'event_volunteers.event_id')
            ->where('events.status', 'active')
            ->where('events.date', '>=', now())
            ->pluck('event_volunteers.event_id')
            ->toArray();

        $events = Event::active()
            ->where('date', '>=', now())
            ->whereNotIn('id', $joinedEventIds)
            ->get();

        $idfDict = $this->buildIdfDictionary();

        // Two-Stage Pipeline - Overhauled to Additive Weighted Model with Dynamic Priorities
        $recommendations = $events->map(function ($event) use ($volunteer, $volunteerFeatures, $idfDict) {
            
            // Read the user's choice
            $priority = $volunteer->primary_priority ?? 'availability';
            if ($priority === 'interests') {
                $weightAvail = 0.20;
                $weightInterest = 0.65; // High interest priority
                $weightLoc = 0.15;
            } elseif ($priority === 'location') {
                $weightAvail = 0.25;
                $weightInterest = 0.10;
                $weightLoc = 0.65; // High location priority
            } else {
                // Default: Interests is now the primary driver to ensure 
                // recommendations change immediately with preferences.
                $weightAvail = 0.30;
                $weightInterest = 0.55;
                $weightLoc = 0.15;
            }

            // 1. Availability Score
            $availabilityScore = $this->calculateAvailabilityScore($volunteer, $event, $weightAvail);

            // 2. Similarity Score (TF-IDF Weighting)
            $eventFeatures = $this->buildEventFeatures($event);
            $similarity    = $this->calculateTfIdfCosineSimilarity($volunteerFeatures, $eventFeatures, $idfDict);

            // Hard gate: require meaningful keyword overlap — generic shared words
            // (like "program") are stripped by the tokenizer stop list, so a
            // score above 0.08 means at least one specific topic word overlaps.
            if ($similarity <= 0.08) {
                return null;
            }

            $similarityScore = $similarity * $weightInterest;

            // 3. Location Score
            $locationScore = $this->calculateLocationScore($volunteer, $event, $weightLoc);

            $finalScore = $availabilityScore + $similarityScore + $locationScore;

            Log::info('[CBF] Event score for volunteer #' . $volunteer->id, [
                'event'           => $event->title,
                'event_id'        => $event->id,
                'availability'    => round($availabilityScore, 4),
                'similarity'      => round($similarityScore, 4),
                'location'        => round($locationScore, 4),
                'final_score'     => round($finalScore, 4),
                'priority'        => $priority,
                'user_features'   => $volunteerFeatures,
                'event_features'  => $eventFeatures,
            ]);

            return [
                'event'            => $event,
                'match_score'      => $finalScore,
                'match_percentage' => min(round($finalScore * 100, 1), 100),
            ];
        })
        ->filter(fn($r) => $r !== null)           // Remove events with zero similarity
        ->filter(fn($r) => $r['match_score'] >= config('match.threshold', 0.70)) // Use config threshold
        ->sortByDesc('match_score')
        ->take($limit)
        ->values();

        Log::info('[CBF] Recommendations for volunteer #' . $volunteerId . ': ' . $recommendations->count() . ' results.', [
            'scores' => $recommendations->map(fn($r) => [
                'event_id'   => $r['event']->id,
                'event'      => $r['event']->title,
                'score'      => $r['match_score'],
                'percentage' => $r['match_percentage'],
            ])->toArray()
        ]);

        // Note: No per-user cache — recommendations are always fresh so that
        // any preference change the user makes is reflected immediately.

        return $recommendations;
    }
    
    /**
     * Recommend volunteers for an event based on required skills
     * 
     * @param int $eventId
     * @param int $topN Number of recommendations to return
     * @param float $threshold Minimum similarity threshold
     * @return array
     */
    public function recommendVolunteersForEvent($eventId, $topN = 20, $threshold = null)
    {
        $threshold = $threshold ?? config('match.threshold', 0.70);
        $event = Event::findOrFail($eventId);
        
        // Build event feature vector
        $eventFeatures = $this->buildEventFeatures($event);
        
        if (empty($eventFeatures)) {
            return []; // No features to match
        }
        
        // Get all users who are NOT already registered and NOT the organizer
        $users = User::where('id', '!=', $event->organizer_id)
            ->whereDoesntHave('volunteerRegistrations', function($q) use ($eventId) {
                $q->where('event_id', $eventId)->where('status', 'registered');
            })
            ->get();
        
        $recommendations = [];
        $idfDict = $this->buildIdfDictionary();
        
        foreach ($users as $user) {
            $userFeatures = $this->buildUserFeatures($user);
            
            if (empty($userFeatures)) {
                continue; // Skip users without skills/interests
            }
            
            // Read the user's priority choice
            $priority = $user->primary_priority ?? 'availability';
            if ($priority === 'interests') {
                $weightAvail = 0.20;
                $weightInterest = 0.65;
                $weightLoc = 0.15;
            } elseif ($priority === 'location') {
                $weightAvail = 0.25;
                $weightInterest = 0.10;
                $weightLoc = 0.65;
            } else {
                $weightAvail = 0.30;
                $weightInterest = 0.55;
                $weightLoc = 0.15;
            }
            
            // 1. Availability Score
            $availabilityScore = $this->calculateAvailabilityScore($user, $event, $weightAvail);

            // 2. Similarity Score (TF-IDF Weighting)
            $similarity = $this->calculateTfIdfCosineSimilarity($userFeatures, $eventFeatures, $idfDict);
            $similarityScore = $similarity * $weightInterest;

            // 3. Location Score
            $locationScore = $this->calculateLocationScore($user, $event, $weightLoc);

            $finalScore = $availabilityScore + $similarityScore + $locationScore;
            
            if ($finalScore >= 0.70) {
                $recommendations[] = [
                    'user' => $user,
                    'score' => round($finalScore, 4),
                    'match_percentage' => min(round($finalScore * 100, 1), 100)
                ];
            }
        }
        
        // Sort by similarity score descending
        usort($recommendations, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        return array_slice($recommendations, 0, $topN);
    }
    
    /**
     * Calculate Availability Score
     * Uses dynamic weight assigned from priority planner
     * Enforces Days + Times (checking event end_time)
     */
    private function calculateAvailabilityScore(User $user, Event $event, float $weight = 0.50): float
    {
        // Neutral baseline
        if (empty($user->availability)) {
            return $weight * 0.50; // Neutral baseline (half score)
        }

        $availText = strtolower($user->availability);
        $eventStart = $event->date->copy()->setTimezone(config('app.timezone'));
        $eventEnd = $event->end_time ? $event->end_time->copy()->setTimezone(config('app.timezone')) : null;
        
        $matchWeight = 0.0;
        $criteriaMet = 0;
        $totalCriteria = 0;

        // 1. Day Check
        $totalCriteria++;
        $dayMatch = false;
        if (str_contains($availText, 'weekend')) {
            if ($eventStart->isWeekend()) $dayMatch = true;
        } elseif (str_contains($availText, 'weekday')) {
            if ($eventStart->isWeekday()) $dayMatch = true;
        } elseif (str_contains($availText, 'any') || str_contains($availText, 'flexible')) {
            $dayMatch = true;
        } else {
            // Check if user listed specific days (Monday, Tuesday, etc.)
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $eventDay = strtolower($eventStart->format('l'));
            foreach ($days as $day) {
                if (str_contains($availText, $day) && $eventDay === $day) {
                    $dayMatch = true;
                    break;
                }
            }
        }
        if ($dayMatch) $criteriaMet++;

        // 2. Time/Schedule Check (Panel Requirement: Check specific shifts)
        $totalCriteria++;
        $timeMatch = false;
        
        // Support both "8am to 1pm" and "8am-1pm"
        preg_match_all('/(\d{1,2}(?::\d{2})?\s*(?:am|pm))\s*(?:to|-|–)\s*(\d{1,2}(?::\d{2})?\s*(?:am|pm))/i', $availText, $matches);
        
        if (!empty($matches[0])) {
            foreach ($matches[1] as $idx => $startTimeStr) {
                $userStart = \Carbon\Carbon::parse($startTimeStr, config('app.timezone'))->format('H:i');
                $userEnd = \Carbon\Carbon::parse($matches[2][$idx], config('app.timezone'))->format('H:i');
                
                $evStartStr = $eventStart->format('H:i');
                $evEndStr = $eventEnd ? $eventEnd->format('H:i') : null;

                // Event must fit within user availability window
                if ($evStartStr >= $userStart && (!$evEndStr || $evEndStr <= $userEnd)) {
                    $timeMatch = true;
                    break;
                }
            }
        } else {
            // Fallback to keyword matching
            if (str_contains($availText, 'morning')) {
                if ($eventStart->hour < 12) $timeMatch = true;
            }
            if (str_contains($availText, 'afternoon') || str_contains($availText, 'evening')) {
                if ($eventStart->hour >= 12) $timeMatch = true;
            }
            if (str_contains($availText, 'flexible') || str_contains($availText, 'anytime') || str_contains($availText, 'any')) {
                $timeMatch = true;
            }
        }
        
        if ($timeMatch) $criteriaMet++;

        // Calculate score using dynamic weight
        $score = ($criteriaMet / $totalCriteria) * $weight;
        
        // Experience modifier (Soft Adjustment)
        if ($user->experience_level && $event->skills_preferred) {
            $skills = strtolower($event->skills_preferred);
            $userExp = strtolower($user->experience_level);
            $needsExpert = str_contains($skills, 'experienced') || str_contains($skills, 'advanced') || str_contains($skills, 'expert');
            if ($needsExpert && $userExp === 'beginner') {
                $score *= 0.8; // Reduce availability weight if skills are a mismatch
            }
        }

        return $score;
    }

    /**
     * Calculate Location Score
     * Uses dynamic weight assigned from priority planner
     */
    private function calculateLocationScore(User $user, Event $event, float $weight = 0.15): float
    {
        // Neutral baseline
        if (!$user->latitude || !$user->longitude || !$event->latitude || !$event->longitude) {
            return $weight * 0.50; // Neutral baseline (half score)
        }

        $distance = $this->haversineDistance(
            (float)$user->latitude, (float)$user->longitude,
            (float)$event->latitude, (float)$event->longitude
        );

        // Within dynamic radius gets full weight
        $defaultRadius = config('match.radius_km', 15.0);
        $userRadius = $user->preferred_radius ?? $defaultRadius;
        $eventRadius = $event->target_radius ?? $defaultRadius;
        $radius = min($userRadius, $eventRadius);
        
        return ($distance <= $radius) ? $weight : ($weight * 0.25);
    }

    /**
     * Calculate distance between two points in km using Haversine formula
     */
    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
    
    /**
     * Build feature string from user profile
     * 
     * @param User $user
     * @return string
     */
    private function buildUserFeatures(User $user)
    {
        $features = [];
        
        // Use only soft constraint fields for similarity
        if (!empty($user->preferences)) {
            $features[] = $user->preferences;
        }
        
        if (!empty($user->interests)) {
            $features[] = $user->interests;
        }
        
        return implode(' ', $features);
    }
    
    /**
     * Build feature string from event (Soft Constraints)
     * 
     * @param Event $event
     * @return string
     */
    private function buildEventFeatures(Event $event)
    {
        $features = [];
        
        if (!empty($event->skills_preferred)) {
            $features[] = $event->skills_preferred;
        }
        
        if (!empty($event->title)) {
            $features[] = $event->title;
        }
        
        if (!empty($event->description)) {
            $features[] = $event->description;
        }
        
        // Note: Location is excluded here as it's handled by Stage 1 priority
        
        return implode(' ', $features);
    }
    
    /**
     * Tokenize text into terms (simple implementation)
     * 
     * @param string $text
     * @return array
     */
    private function tokenize($text)
    {
        // Convert to lowercase
        $text = strtolower($text);
        
        // Remove special characters, keep only alphanumeric and spaces
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);
        
        // Split into words
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        // Remove common English stop words
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'from', 'as', 'is', 'was', 'are', 'were', 'be', 'been', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might', 'can',
            // Domain-generic words that appear in almost every event and cause false matches
            'program', 'event', 'volunteer', 'volunteering', 'volunteers', 'activity',
            'activities', 'community', 'service', 'services', 'project', 'projects',
            'initiative', 'outreach', 'davao', 'city', 'philippines', 'join', 'help',
        ];
        
        $words = array_filter($words, function($word) use ($stopWords) {
            return !in_array($word, $stopWords) && strlen($word) > 2;
        });
        
        return array_values($words);
    }
    
    /**
     * Build corpus frequency for TF-IDF
     */
    private function buildIdfDictionary()
    {
        $cacheKey = 'tfidf_idf_dictionary';
        $cached = Cache::get($cacheKey);
        
        if ($cached) {
            return $cached;
        }

        $events = Event::active()->get();
        // If there are no events, use 1 as fallback to avoid division by zero
        $totalDocuments = max(1, $events->count());
        
        $documentFrequency = [];
        
        foreach ($events as $event) {
            $features = $this->buildEventFeatures($event);
            $tokens = array_unique($this->tokenize($features));
            
            foreach ($tokens as $token) {
                $documentFrequency[$token] = ($documentFrequency[$token] ?? 0) + 1;
            }
        }
        
        $idf = [];
        
        foreach ($documentFrequency as $term => $count) {
            $idf[$term] = log($totalDocuments / $count) + 1; // IDF weighting
        }
        
        $result = ['idf' => $idf, 'total_docs' => $totalDocuments];
        Cache::put($cacheKey, $result, 3600); // 1 hour cache
        
        return $result;
    }

    /**
     * Calculate TF-IDF Cosine Similarity between two feature strings
     * 
     * @param string $features1
     * @param string $features2
     * @param array $idfDict
     * @return float
     */
    private function calculateTfIdfCosineSimilarity($features1, $features2, $idfDict = null)
    {
        if (empty($features1) || empty($features2)) {
            return 0.0;
        }
        
        // Tokenize both feature sets
        $tokens1 = $this->tokenize($features1);
        $tokens2 = $this->tokenize($features2);
        
        if (empty($tokens1) || empty($tokens2)) {
            return 0.0;
        }

        if (!$idfDict) {
            $idfDict = $this->buildIdfDictionary();
        }
        $idf = $idfDict['idf'];
        $totalDocs = $idfDict['total_docs'];
        
        // Create vocabulary (unique terms from both)
        $vocabulary = array_unique(array_merge($tokens1, $tokens2));
        
        // Count term frequencies
        $tf1 = [];
        $tf2 = [];
        
        foreach ($tokens1 as $token) {
            $tf1[$token] = ($tf1[$token] ?? 0) + 1;
        }
        
        foreach ($tokens2 as $token) {
            $tf2[$token] = ($tf2[$token] ?? 0) + 1;
        }
        
        $totalTokens1 = max(1, count($tokens1));
        $totalTokens2 = max(1, count($tokens2));

        // Calculate cosine similarity with TF-IDF weights
        $dotProduct = 0;
        $magnitude1 = 0;
        $magnitude2 = 0;
        
        foreach ($vocabulary as $term) {
            $count1 = $tf1[$term] ?? 0;
            $count2 = $tf2[$term] ?? 0;
            
            // Term Frequency (TF)
            $termTf1 = $count1 / $totalTokens1;
            $termTf2 = $count2 / $totalTokens2;
            
            // Inverse Document Frequency (IDF) - Default assuming term only exists here if not found in IDF dict
            $termIdf = $idf[$term] ?? (log($totalDocs) + 1);
            
            // TF-IDF weights
            $weight1 = $termTf1 * $termIdf;
            $weight2 = $termTf2 * $termIdf;
            
            $dotProduct += $weight1 * $weight2;
            $magnitude1 += $weight1 ** 2;
            $magnitude2 += $weight2 ** 2;
        }
        
        $magnitude1 = sqrt($magnitude1);
        $magnitude2 = sqrt($magnitude2);
        
        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0.0;
        }
        
        return $dotProduct / ($magnitude1 * $magnitude2);
    }
    
    /**
     * Calculate accuracy metrics for the recommendation system
     * 
     * @return array
     */
    public function getAccuracyMetrics()
    {
        $users = User::whereNotNull('preferences')->orWhereNotNull('interests')->get();
        $events = Event::active()->get();
        
        if ($users->isEmpty() || $events->isEmpty()) {
            return [
                'overall_accuracy' => 0,
                'top_n_accuracy' => 0,
                'avg_similarity' => 0,
                'total_comparisons' => 0
            ];
        }
        
        $totalComparisons = 0;
        $aboveThreshold = 0;
        $totalSimilarity = 0;
        $usersWithRecommendations = 0;
        
        $idfDict = $this->buildIdfDictionary();
        
        foreach ($users as $user) {
            $userFeatures = $this->buildUserFeatures($user);
            
            if (empty($userFeatures)) {
                continue;
            }
            
            $hasRecommendation = false;
            
            foreach ($events as $event) {
                $priority = $user->primary_priority ?? 'availability';
                if ($priority === 'interests') {
                    $weightAvail = 0.35; $weightInterest = 0.50; $weightLoc = 0.15;
                } elseif ($priority === 'location') {
                    $weightAvail = 0.35; $weightInterest = 0.15; $weightLoc = 0.50;
                } else {
                    $weightAvail = 0.50; $weightInterest = 0.35; $weightLoc = 0.15;
                }

                // 1. Availability Score
                $availabilityScore = $this->calculateAvailabilityScore($user, $event, $weightAvail);

                // 2. TF-IDF Similarity Score
                $eventFeatures = $this->buildEventFeatures($event);
                $similarity = $this->calculateTfIdfCosineSimilarity($userFeatures, $eventFeatures, $idfDict);
                $similarityScore = $similarity * $weightInterest;

                // 3. Location Score
                $locationScore = $this->calculateLocationScore($user, $event, $weightLoc);

                $finalScore = $availabilityScore + $similarityScore + $locationScore;
                
                $totalComparisons++;
                $totalSimilarity += $finalScore;
                
                if ($finalScore >= 0.70) {
                    $aboveThreshold++;
                    $hasRecommendation = true;
                }
            }
            
            if ($hasRecommendation) {
                $usersWithRecommendations++;
            }
        }
        
        return [
            'overall_accuracy' => $totalComparisons > 0 ? round(($aboveThreshold / $totalComparisons) * 100, 2) : 0,
            'top_n_accuracy' => $users->count() > 0 ? round(($usersWithRecommendations / $users->count()) * 100, 2) : 0,
            'avg_similarity' => $totalComparisons > 0 ? round($totalSimilarity / $totalComparisons, 4) : 0,
            'total_comparisons' => $totalComparisons,
            'above_threshold' => $aboveThreshold,
            'users_with_recommendations' => $usersWithRecommendations
        ];
    }
}
