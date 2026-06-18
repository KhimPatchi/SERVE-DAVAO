<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the Google-only login/registration page.
     * Manual registration via form is no longer supported.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Manual registration via form is disabled.
     * All registration happens through Google OAuth.
     */
    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('login')
            ->with('error', 'Registration is done automatically via Google Sign-In. Please use the button below.');
    }
}
