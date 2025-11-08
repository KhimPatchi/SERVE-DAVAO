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

        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Access denied. Admin only.');
        }

        return $next($request);
    }
}