<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Event;
use App\Policies\EventPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Event::class => EventPolicy::class,
    ];

    public function boot(): void
    {
        // Add no-cache headers for all responses
        Response::macro('noCache', function ($content) {
            return Response::make($content)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        });
    }
}