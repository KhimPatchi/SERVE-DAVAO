<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$events = DB::table('events')->select('id', 'title', 'image')->get();

echo "Events in database:\n\n";
foreach ($events as $event) {
    echo "ID: {$event->id}\n";
    echo "Title: {$event->title}\n";
    echo "Image: " . ($event->image ?? 'NULL') . "\n";
    if ($event->image) {
        $fullPath = storage_path('app/public/' . $event->image);
        echo "Full path: {$fullPath}\n";
        echo "File exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
    }
    echo "\n---\n\n";
}
