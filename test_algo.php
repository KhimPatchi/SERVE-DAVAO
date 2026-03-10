<?php
// test_algo.php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$volunteer = App\Models\User::where('role', 'volunteer')->first();
$events = App\Models\Event::active()->get();

if (!$volunteer || $events->isEmpty()) {
    echo "Missing volunteer or active events.";
    exit;
}

echo "Volunteer: " . $volunteer->name . "\n";
echo "Availability: " . $volunteer->availability . "\n";
echo "Priority: " . $volunteer->primary_priority . "\n";

$service = app(App\Services\ContentBasedFilteringService::class);

// Force clear cache for this volunteer
Illuminate\Support\Facades\Cache::forget('recommendations_' . $volunteer->id);

$recommendations = $service->recommendEventsForVolunteer($volunteer->id);

echo "Total Recommendations found: " . $recommendations->count() . "\n";

if ($recommendations->isNotEmpty()) {
    foreach ($recommendations as $rec) {
        $event = $rec['event'];
        echo "Event: " . $event->title . "\n";
        echo "Score: " . $rec['match_score'] . "\n";
        echo "Percentage: " . $rec['match_percentage'] . "%\n";
    }
}

// Dig into the raw features
$methodUser = new ReflectionMethod($service, 'buildUserFeatures');
$methodUser->setAccessible(true);
$userFeatures = $methodUser->invoke($service, $volunteer);

echo "\nUser Features string: " . $userFeatures . "\n";

$methodDict = new ReflectionMethod($service, 'buildIdfDictionary');
$methodDict->setAccessible(true);
$dict = $methodDict->invoke($service);
echo "IDF Dictionary size: " . count($dict['idf']) . "\n";

$methodSim = new ReflectionMethod($service, 'calculateTfIdfCosineSimilarity');
$methodSim->setAccessible(true);
$methodEvent = new ReflectionMethod($service, 'buildEventFeatures');
$methodEvent->setAccessible(true);

foreach ($events as $event) {
    echo "========================================\n";
    echo "Event: " . $event->title . "\n";
    $eventFeatures = $methodEvent->invoke($service, $event);
    echo "Event Features string: " . $eventFeatures . "\n";
    
    $sim = $methodSim->invoke($service, $userFeatures, $eventFeatures, $dict);
    echo "Base TF-IDF Similarity Score (unweighted): " . $sim . "\n";
}

echo "\nCompleted.\n";
