<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventVolunteer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class PreferenceService
{
    /**
     * Get popular preference tags from existing events
     * 
     * @param int $limit Maximum number of tags to return
     * @return Collection
     */
    public function getPopularTags($limit = 20): Collection
    {
        return Cache::remember('popular_preference_tags', 3600, function () use ($limit) {
            $tags = Event::active()
                ->whereNotNull('skills_preferred')
                ->where('skills_preferred', '!=', '')
                ->pluck('skills_preferred')
                ->flatMap(function ($preferences) {
                    // Split by comma and filter empty values
                    return array_filter(
                        explode(',', $preferences),
                        fn($tag) => !empty(trim($tag))
                    );
                })
                ->map(function ($tag) {
                    // Normalize: trim whitespace and convert to lowercase for consistency
                    return strtolower(trim($tag));
                })
                ->filter(function ($tag) {
                    // Remove empty tags after trimming
                    return !empty($tag) && strlen($tag) > 1;
                })
                ->countBy()
                ->sortDesc()
                ->take($limit);

            // Convert to array with tag name and event count
            // Use ucwords to capitalize first letter of each word for display
            return $tags->map(function ($count, $tag) use ($tags) {
                return [
                    'name' => ucwords($tag), // Capitalize for display
                    'event_count' => $count,
                    'popularity' => round($count / max($tags->values()->toArray()) * 100, 0)
                ];
            })->values();
        });
    }

    /**
     * Get all unique categories from events
     * 
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return Cache::remember('event_categories', 3600, function () {
            // Extract common words from event titles and descriptions
            // This is  a simple implementation - can be enhanced with NLP
            $categories = Event::active()
                ->get()
                ->flatMap(function ($event) {
                    $words = [];
                    
                    // Extract from title
                    if ($event->title) {
                        $titleWords = preg_split('/\s+/', strtolower($event->title));
                        $words = array_merge($words, $titleWords);
                    }
                    
                    return $words;
                })
                ->filter(function ($word) {
                    // Filter out common words and keep meaningful categories
                    $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'event', 'program', 'drive'];
                    return !in_array($word, $stopWords) && strlen($word) > 3;
                })
                ->countBy()
                ->sortDesc()
                ->take(10)
                ->keys();

            return $categories;
        });
    }

    /**
     * Get suggested preferences for a user based on their behavior
     * 
     * @param int $userId
     * @return Collection
     */
    public function getSuggestedPreferences($userId): Collection
    {
        // Get events the user has joined
        $joinedEvents = EventVolunteer::where('volunteer_id', $userId)
            ->join('events', 'events.id', '=', 'event_volunteers.event_id')
            ->pluck('events.skills_preferred');

        // Extract preferences from joined events
        $implicitPreferences = $joinedEvents
            ->flatMap(function ($preferences) {
                return explode(',', $preferences ?? '');
            })
            ->map(function ($tag) {
                return trim($tag);
            })
            ->filter(function ($tag) {
                return !empty($tag);
            })
            ->countBy()
            ->sortDesc()
            ->take(5)
            ->keys();

        return $implicitPreferences;
    }

    /**
     * Clear preference tags cache
     * Call this when new events are created or updated
     * 
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget('popular_preference_tags');
        Cache::forget('event_categories');
    }
}
