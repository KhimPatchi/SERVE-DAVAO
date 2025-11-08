<?php

namespace App\Http\Controllers\Verify;

use App\Http\Controllers\Controller;
use App\Models\OrganizerVerification;
use App\Services\OrganizerVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizerVerificationController extends Controller
{
    protected $verificationService;

    public function __construct(OrganizerVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    // Show verification form
    public function create()
    {
        // Check if user already has pending verification
        if (Auth::user()->hasPendingVerification()) {
            return redirect()->route('organizer.verification.status')
                             ->with('info', 'You already have a pending verification request. Please wait for admin approval.');
        }

        // Check if user is already verified
        if (Auth::user()->isVerifiedOrganizer()) {
            return redirect()->route('dashboard')
                             ->with('success', 'You are already a verified organizer. You can now create events!');
        }

        // Check if user has rejected verification
        if (Auth::user()->hasRejectedVerification()) {
            $latestVerification = OrganizerVerification::getLatestForUser(Auth::id());
            return view('organizer.verification', [
                'previousApplication' => $latestVerification
            ]);
        }

        return view('organizer.verification');
    }

    // Store verification request
    public function store(Request $request)
    {
        // Check if user already has pending verification
        if (Auth::user()->hasPendingVerification()) {
            return redirect()->route('organizer.verification.status')
                             ->with('info', 'You already have a pending verification request. Please wait for admin approval.');
        }

        // Check if user is already verified
        if (Auth::user()->isVerifiedOrganizer()) {
            return redirect()->route('dashboard')
                             ->with('success', 'You are already a verified organizer.');
        }

        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'organization_type' => 'required|string|in:non_profit,school,community,business,individual,other',
            'identification_number' => 'required|string|max:50|regex:/^[A-Za-z0-9\-]+$/',
            'identification_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'phone' => 'required|string|max:20|regex:/^[\d+\-\s()]+$/',
            'address' => 'required|string|max:500',
        ], [
            'identification_number.regex' => 'The identification number can only contain letters, numbers, and hyphens.',
            'phone.regex' => 'The phone number can only contain numbers, plus sign, hyphens, spaces, and parentheses.',
            'identification_document.required' => 'Please upload an identification document.',
            'identification_document.mimes' => 'The document must be a JPG, PNG, or PDF file.',
            'identification_document.max' => 'The document must not exceed 2MB.',
        ]);

        try {
            // Use the service to handle file upload
            $filePath = $this->verificationService->handleFileUpload($request->file('identification_document'));

            // Create verification record with applicant_name from authenticated user
            OrganizerVerification::create([
                'user_id' => Auth::id(),
                'applicant_name' => Auth::user()->name, // Automatically use logged-in user's name
                'organization_name' => $validated['organization_name'],
                'organization_type' => $validated['organization_type'],
                'identification_number' => $validated['identification_number'],
                'identification_document_path' => $filePath,
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'status' => 'pending',
            ]);

            // Redirect to status page with success message
            return redirect()->route('organizer.verification.status')
                             ->with('success', 'Your organizer verification request has been submitted successfully! It will be reviewed by our admin team within 1-3 business days.');

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Verification submission failed: ' . $e->getMessage());
            
            // Return with detailed error message
            return redirect()->back()
                             ->with('error', 'Failed to submit verification request. Please try again. Error: ' . $e->getMessage())
                             ->withInput();
        }
    }

    // Show verification status
    public function status()
    {
        $verification = Auth::user()->organizerVerification;

        if (!$verification) {
            return redirect()->route('organizer.verification.create')
                             ->with('info', 'Please submit an organizer verification application first.');
        }

        return view('organizer.verification-status', compact('verification'));
    }
}