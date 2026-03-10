<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::find(10);
$user->update(['primary_priority' => 'interests']);
echo "User #10 priority set to: " . $user->primary_priority . "\n";
