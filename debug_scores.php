<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

use App\Models\User;
use App\Models\Event;
use App\Models\EventVolunteer;
use App\Services\ContentBasedFilteringService;

$email = 'khimdavin25@gmail.com';
$user = User::where('email', $email)->first();

if (!$user) { echo "User not found.\n"; exit; }

$cbf = app(ContentBasedFilteringService::class);

// Reflect private methods
$buildUserFeatures  = new ReflectionMethod($cbf, 'buildUserFeatures');  $buildUserFeatures->setAccessible(true);
$buildEventFeatures = new ReflectionMethod($cbf, 'buildEventFeatures'); $buildEventFeatures->setAccessible(true);
$calcSimilarity     = new ReflectionMethod($cbf, 'calculateTfIdfCosineSimilarity'); $calcSimilarity->setAccessible(true);
$calcAvailability   = new ReflectionMethod($cbf, 'calculateAvailabilityScore'); $calcAvailability->setAccessible(true);
$calcLocation       = new ReflectionMethod($cbf, 'calculateLocationScore'); $calcLocation->setAccessible(true);
$buildIdf           = new ReflectionMethod($cbf, 'buildIdfDictionary'); $buildIdf->setAccessible(true);

$userFeatures = $buildUserFeatures->invoke($cbf, $user);
$idfDict      = $buildIdf->invoke($cbf);

$priority = $user->primary_priority ?? 'availability';
if ($priority === 'interests') {
    $wA = 0.20; $wI = 0.65; $wL = 0.15;
} elseif ($priority === 'location') {
    $wA = 0.25; $wI = 0.10; $wL = 0.65;
} else {
    $wA = 0.30; $wI = 0.55; $wL = 0.15;
}

$threshold = config('match.threshold', 0.60);

echo "============================================================\n";
echo "  RECOMMENDATION SCORE REPORT\n";
echo "  User    : {$user->name} ({$user->email})\n";
echo "  Profile : Preferences=\"{$user->preferences}\" | Interests=\"{$user->interests}\"\n";
echo "  Avail   : {$user->availability}\n";
echo "  Priority: $priority | Threshold: $threshold\n";
echo "  Weights : Availability=$wA | Similarity=$wI | Location=$wL\n";
echo "============================================================\n\n";

$joinedIds = EventVolunteer::where('volunteer_id', $user->id)
    ->whereIn('event_volunteers.status', ['registered', 'attended'])
    ->join('events', 'events.id', '=', 'event_volunteers.event_id')
    ->where('events.status', 'active')
    ->where('events.date', '>=', now())
    ->pluck('event_volunteers.event_id')
    ->toArray();

$events = Event::active()->where('date', '>=', now())->get();

foreach ($events as $event) {
    $alreadyJoined = in_array($event->id, $joinedIds);
    $eventFeatures = $buildEventFeatures->invoke($cbf, $event);
    $similarity    = $calcSimilarity->invoke($cbf, $userFeatures, $eventFeatures, $idfDict);
    $avail         = $calcAvailability->invoke($cbf, $user, $event, $wA);
    $loc           = $calcLocation->invoke($cbf, $user, $event, $wL);
    $simScore      = $similarity * $wI;
    $total         = $avail + $simScore + $loc;
    $pct           = min(round($total * 100, 1), 100);

    $gateKilled  = ($similarity <= 0.02) ? ' ⛔ GATE (sim too low)' : '';
    $status      = $alreadyJoined ? '🔒 ALREADY JOINED'
                 : ($gateKilled   ? '❌ NO MATCH' . $gateKilled
                 : ($total >= $threshold ? '✅ RECOMMENDED' : '❌ BELOW THRESHOLD'));

    echo "Event #{$event->id}: \"{$event->title}\"\n";
    echo "  Date            : " . $event->date->format('D, M j Y  H:i') . "\n";
    echo "  ┌─ Availability : " . round($avail,    4) . " / {$wA}  (max {$wA})\n";
    echo "  ├─ Similarity   : " . round($similarity,4) . " → weighted " . round($simScore,4) . " / {$wI}  (raw cosine)\n";
    echo "  ├─ Location     : " . round($loc,       4) . " / {$wL}  (max {$wL})\n";
    echo "  └─ TOTAL SCORE  : " . round($total,     4) . "  ({$pct}%)  → $status\n\n";
}

echo "============================================================\n";
echo "  FINAL RECOMMENDATIONS (returned by algorithm)\n";
echo "============================================================\n";
$recs = $cbf->recommendEventsForVolunteer($user->id, 20);
if ($recs->isEmpty()) {
    echo "  (none — no events passed the threshold)\n";
} else {
    foreach ($recs as $r) {
        echo "  ✅ [{$r['match_percentage']}% match] #{$r['event']->id}: {$r['event']->title}\n";
    }
}
echo "\n";
