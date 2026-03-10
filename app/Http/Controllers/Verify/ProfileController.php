<?php

namespace App\Http\Controllers\Verify;

use App\Http\Controllers\Controller;
use App\Services\PreferenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    protected $preferenceService;

    public function __construct(PreferenceService $preferenceService)
    {
        $this->preferenceService = $preferenceService;
    }

    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        $popularTags = $this->preferenceService->getPopularTags(20);
        $suggestedPreferences = $this->preferenceService->getSuggestedPreferences(auth()->id());
        
        return view('profile.edit', [
            'user' => Auth::user(),
            'popularTags' => $popularTags,
            'suggestedPreferences' => $suggestedPreferences
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Profile Update Request:', $request->all());
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // Add missing fields
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            // Volunteer preferences (optional)
            'preferences' => 'nullable|string|max:500',
            'interests' => 'nullable|string|max:500',
            'experience_level' => 'nullable|in:beginner,intermediate,advanced',
            'availability' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'preferred_radius' => 'nullable|numeric|min:1|max:100',
            'primary_priority' => 'nullable|in:availability,interests,location'
        ]);

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show preferences edit form
     */
    public function editPreferences()
    {
        $popularTags = $this->preferenceService->getPopularTags(30);
        $suggestedPreferences = $this->preferenceService->getSuggestedPreferences(auth()->id());

        return view('profile.preferences', [
            'user' => auth()->user(),
            'popularTags' => $popularTags,
            'suggestedPreferences' => $suggestedPreferences
        ]);
    }

    /**
     * Update volunteer preferences
     */
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'preferences' => 'nullable|string|max:500',
            'interests' => 'nullable|string|max:500',
            'experience_level' => 'nullable|in:beginner,intermediate,advanced',
            'availability' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'preferred_radius' => 'nullable|numeric|min:1|max:100',
            'primary_priority' => 'nullable|in:availability,interests,location'
        ]);

        auth()->user()->update($validated);

        return redirect()->back()->with('success', 'Your preferences have been updated! 🎯');
    }

    /**
     * Get popular preference tags (API endpoint)
     */
    public function getPopularTags()
    {
        $tags = $this->preferenceService->getPopularTags(20);

        return response()->json([
            'success' => true,
            'tags' => $tags
        ]);
    }
}