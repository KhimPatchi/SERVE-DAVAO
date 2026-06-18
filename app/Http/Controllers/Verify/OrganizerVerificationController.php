<?php

namespace App\Http\Controllers\Verify;

use App\Http\Controllers\Controller;
use App\Models\OrganizerVerification;
use App\Services\IDAnalyzerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrganizerVerificationController extends Controller
{
    protected $idAnalyzerService;

    public function __construct(IDAnalyzerService $idAnalyzerService)
    {
        $this->idAnalyzerService = $idAnalyzerService;
    }

    // Show verification form
    public function create()
    {
        // Check if user already has pending verification
        if (Auth::user()->hasPendingVerification()) {
            return redirect()->route('organizer.verification.status')
                             ->with('info', 'Your verification is being processed. Please wait a moment.');
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

    // Store verification request with ID Analyzer + live selfie integration
    public function store(Request $request)
    {
        // Check if user already has pending verification
        if (Auth::user()->hasPendingVerification()) {
            return redirect()->route('organizer.verification.status')
                             ->with('info', 'You already have a pending verification request.');
        }

        // Check if user is already verified
        if (Auth::user()->isVerifiedOrganizer()) {
            return redirect()->route('dashboard')
                             ->with('success', 'You are already a verified organizer.');
        }

        $validated = $request->validate([
            'organization_name'       => 'required|string|max:255',
            'organization_type'       => 'required|string|in:non_profit,school,community,business,individual,other',
            'identification_number'   => 'required|string|max:50|regex:/^[A-Za-z0-9\-]+$/',
            'identification_document' => 'required|file|mimes:jpg,jpeg,png|max:10240', // 10MB max
            'selfie_data'             => 'required|string',  // base64 PNG from live camera
            'phone'                   => 'required|string|max:20|regex:/^[\d+\-\s()]+$/',
            'address'                 => 'required|string|max:500',
        ], [
            'identification_number.regex'   => 'The identification number can only contain letters, numbers, and hyphens.',
            'phone.regex'                   => 'The phone number can only contain numbers, plus sign, hyphens, spaces, and parentheses.',
            'identification_document.required' => 'Please upload a clear photo of your government-issued ID.',
            'identification_document.mimes' => 'The ID document must be a JPG or PNG image.',
            'identification_document.max'   => 'The ID document must not exceed 10MB.',
            'selfie_data.required'          => 'A live selfie is required. Please use your camera to take a photo.',
        ]);

        try {
            $idDocument = $request->file('identification_document');

            // ── Step 1: Convert base64 live selfie → UploadedFile ──────────────
            $selfieBase64 = $request->input('selfie_data');
            $selfie = $this->idAnalyzerService->createUploadedFileFromBase64($selfieBase64);

            // ── Step 2: Verify document + live selfie with ID Analyzer API ──────
            Log::info('Starting live-selfie ID verification for user: ' . Auth::id());
            $verificationResult = $this->idAnalyzerService->verifyDocument($idDocument, $selfie, (string)Auth::id());

            Log::info('ID Analyzer result', ['result' => $verificationResult]);

            // ── Step 3: Store documents permanently ──────────────────────────────
            $documentPath = $this->idAnalyzerService->storeVerificationDocument($idDocument, Auth::id());
            $selfiePath   = $this->idAnalyzerService->storeVerificationSelfie($selfie, Auth::id());

            // Clean up the temp selfie file used for API call
            $selfie->getRealPath() && @unlink($selfie->getRealPath());

            // ── Step 4: Determine verification status ─────────────────────────
            $isVerified      = $verificationResult['verified'] ?? false;
            $status          = $isVerified ? 'approved' : 'rejected';
            $approvedAt      = $isVerified ? now() : null;
            $rejectedAt      = $isVerified ? null : now();
            $rejectionReason = !$isVerified
                ? $this->idAnalyzerService->getVerificationStatusMessage($verificationResult)
                : null;

            // ── Step 5: Create verification record ────────────────────────────
            $verification = OrganizerVerification::create([
                'user_id'                       => Auth::id(),
                'applicant_name'                => Auth::user()->name,
                'organization_name'             => $validated['organization_name'],
                'organization_type'             => $validated['organization_type'],
                'identification_number'         => $validated['identification_number'],
                'identification_document_path'  => $documentPath,
                'selfie_path'                   => $selfiePath,
                'phone'                         => $validated['phone'],
                'address'                       => $validated['address'],
                'status'                        => $status,
                'rejection_reason'              => $rejectionReason,
                'approved_at'                   => $approvedAt,
                'rejected_at'                   => $rejectedAt,
                // ID Analyzer metadata
                'document_type'                 => $verificationResult['document_type'] ?? null,
                'verification_score'            => $verificationResult['confidence_score'] ?? null,
                'face_match_score'              => $verificationResult['face_match_score'] ?? null,
                'liveness_score'                => $verificationResult['liveness_score'] ?? null,
                'issuing_country'               => $verificationResult['issuing_country'] ?? null,
                'verification_data'             => $verificationResult,
                'verified_at'                   => $isVerified ? now() : null,
            ]);

            // ── Step 6: Redirect based on result ───────────────────────────────
            if ($isVerified) {
                return redirect()->route('organizer.verification.status')
                                 ->with('success', '🎉 Your identity has been verified! You can now create events.');
            } else {
                return redirect()->route('organizer.verification.status')
                                 ->with('error', $rejectionReason . ' Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('Verification submission failed', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                             ->with('error', 'Failed to process verification. Please ensure your ID photo is clear and try again.')
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