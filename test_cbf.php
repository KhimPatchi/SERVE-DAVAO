<?php
use App\Models\User;
use App\Models\Event;
use App\Services\ContentBasedFilteringService;

// Try with the provided email, correcting the obvious typo if necessary
$email = 'atsuke.ackerman@gmail.com';
$u = User::where('email', 'LIKE', '%atsuke.ackerman@gmail.com%')->first();

if ($u) {
    echo "User found: " . $u->name . "\n";
    echo "Preferences: " . $u->preferences . "\n";
    echo "Interests: " . $u->interests . "\n";
    echo "Availability: " . $u->availability . "\n";
    echo "Experience: " . $u->experience_level . "\n";
    echo "Radius: " . $u->preferred_radius . "\n";

    $cbf = app(ContentBasedFilteringService::class);
    
    // Simulate the inside of recommendEventsForVolunteer
    $volunteerFeatures = (new ReflectionMethod($cbf, 'buildUserFeatures'))->invoke($cbf, $u);
    echo "Volunteer Features: " . json_encode($volunteerFeatures) . "\n";
    
    $events = Event::active()->where('date', '>=', now())->get();
    echo "Found " . $events->count() . " active future events.\n\n";
    
    foreach ($events as $event) {
        $availabilityScore = (new ReflectionMethod($cbf, 'calculateAvailabilityScore'))->invoke($cbf, $u, $event);
        
        $eventFeatures = (new ReflectionMethod($cbf, 'buildEventFeatures'))->invoke($cbf, $event);
        $similarity = (new ReflectionMethod($cbf, 'calculateCosineSimilarity'))->invoke($cbf, $volunteerFeatures, $eventFeatures);
        $similarityScore = $similarity * 0.30;
        
        $locationScore = (new ReflectionMethod($cbf, 'calculateLocationScore'))->invoke($cbf, $u, $event);
        
        $finalScore = $availabilityScore + $similarityScore + $locationScore;
        
        echo "Event: " . $event->title . "\n";
        echo " - Avail: " . $availabilityScore . "\n";
        echo " - Simil: " . $similarityScore . " (raw: $similarity)\n";
        echo " - Locat: " . $locationScore . "\n";
        echo " - Total: " . $finalScore . "\n\n";
    }
} else {
    echo "User not found\n";
}
