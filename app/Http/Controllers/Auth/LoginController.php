<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login based on their role.
     */
    protected function authenticated(Request $request, $user)
    {
        // Redirect admins to admin dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Redirect all other users to regular dashboard
        return redirect()->route('dashboard');
    }

    /**
     * Get the login username to be used by the controller.
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}