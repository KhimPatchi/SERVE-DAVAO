<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\PreferenceService;

$service = new PreferenceService();

echo "Popular Tags from PreferenceService:\n\n";

$tags = $service->getPopularTags(20);

if ($tags->isEmpty()) {
    echo "NO TAGS FOUND!\n";
} else {
    foreach ($tags as $tag) {
        echo "Tag: {$tag['name']}\n";
        echo "Event Count: {$tag['event_count']}\n";
        echo "Popularity: {$tag['popularity']}%\n";
        echo "---\n";
    }
    echo "\nTotal Tags: " . $tags->count() . "\n";
}
