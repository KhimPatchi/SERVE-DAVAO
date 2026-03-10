<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Admin role has been removed from the system.
        // All admin features are being deleted.
        return redirect()->route('dashboard')->with('error', 'Access denied. The admin system is no longer active.');

        return $next($request);
    }
}