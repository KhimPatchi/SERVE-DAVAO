<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the Google-only login page.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Email/password login is disabled — all auth goes through Google.
     * Redirect any POST attempts to the login page.
     */
    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('login')
            ->with('error', 'Please use Google Sign-In to access ServeDavao.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
