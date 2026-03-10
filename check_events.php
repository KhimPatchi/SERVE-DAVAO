<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Event;

echo "Checking Events and their skills_preferred:\n\n";

$events = Event::all(['id', 'title', 'skills_preferred']);

foreach ($events as $event) {
    echo "ID: {$event->id}\n";
    echo "Title: {$event->title}\n";
    echo "Skills Preferred: " . ($event->skills_preferred ?? '[EMPTY]') . "\n";
    echo "---\n";
}

echo "\nTotal Events: " . $events->count() . "\n";
echo "Events with skills_preferred: " . Event::whereNotNull('skills_preferred')->count() . "\n";
