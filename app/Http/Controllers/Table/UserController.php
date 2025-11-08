<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['organizedEvents', 'volunteeringEvents'])->paginate(10);
        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:volunteer,organizer,admin',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function getUserVolunteerStats(User $user = null)
    {
        $targetUser = $user ?: auth()->user();
        
        $stats = $targetUser->getVolunteerStats();
        
        // If it's an API request or AJAX call
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        }
        
        // For web requests, return the stats
        return $stats;
    }
}