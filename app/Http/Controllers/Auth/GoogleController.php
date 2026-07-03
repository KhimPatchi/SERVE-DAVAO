<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\CloudinaryService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    // Step 1: Redirect to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    // Step 2: Handle Google callback
    public function handleGoogleCallback()
    {
        try {
            \Log::info('=== GOOGLE LOGIN STARTED ===');
            
            // Get user info from Google
            $googleUser = Socialite::driver('google')->stateless()->user();
            \Log::info('Google user received', ['email' => $googleUser->getEmail()]);

            // Find user by email or google_id
            $user = User::where('email', $googleUser->getEmail())
                        ->orWhere('google_id', $googleUser->getId())
                        ->first();

            // Upload Google avatar to Cloudinary for persistent storage
            $avatarPath = null;
            if ($googleUser->getAvatar()) {
                try {
                    $contents   = file_get_contents($googleUser->getAvatar());
                    $cloudinary = app(CloudinaryService::class);
                    $avatarPath = $cloudinary->upload($contents, 'avatars');
                    \Log::info('Avatar uploaded to Cloudinary successfully');
                } catch (\Exception $e) {
                    \Log::warning('Cloudinary avatar upload failed, using remote Google URL');
                    $avatarPath = $googleUser->getAvatar();
                }
            }

            if ($user) {
                // Update existing user
                $user->update([
                    'name' => $googleUser->getName() ?? $user->name,
                    'avatar' => $avatarPath ?? $user->avatar,
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(),
                ]);
                \Log::info('Existing user updated', ['user_id' => $user->id]);
            } else {
                // Create new user with CORRECT role
                $user = User::create([
                    'name' => $googleUser->getName() ?? 'Google User',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $avatarPath,
                    'role' => 'user', // ✅ FIXED: Use 'user' not 'volunteer'
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(24)),
                ]);
                \Log::info('New user created', ['user_id' => $user->id]);
                event(new Registered($user));
            }

            // Log the user in
            Auth::login($user, true);
            \Log::info('User logged in successfully');

            return redirect('/dashboard')->with('success', 'Successfully logged in with Google!');

        } catch (\Exception $e) {
            \Log::error('=== GOOGLE LOGIN FAILED ===');
            \Log::error('Error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile());
            \Log::error('Line: ' . $e->getLine());
            
            // Show the actual error
            return redirect('/login')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}