<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class IDAnalyzerService
{
    private string $apiKey;
    private string $region;
    private bool $verifyFace;
    private array $acceptedDocuments;
    private float $confidenceThreshold;
    private string $baseUrl;
    private ?string $profileId;

    public function __construct()
    {
        $this->apiKey = config('services.idanalyzer.api_key');
        $this->region = config('services.idanalyzer.region', 'US');
        $this->verifyFace = config('services.idanalyzer.verify_face', false);
        $this->acceptedDocuments = explode(',', config('services.idanalyzer.accepted_documents', 'passport,driverlicense,nationalid'));
        $this->confidenceThreshold = config('services.idanalyzer.confidence_threshold', 0.7);
        $this->profileId = config('services.idanalyzer.profile_id');

        // Set API endpoint based on region
        $this->baseUrl = $this->getRegionalApiEndpoint($this->region);
    }

    /**
     * Get the regional API endpoint
     */
    private function getRegionalApiEndpoint(string $region): string
    {
        return match(strtoupper($region)) {
            'EU' => 'https://api-eu.idanalyzer.com',
            'AS' => 'https://api-as.idanalyzer.com',
            default => 'https://api.idanalyzer.com',
        };
    }

    /**
     * Verify an ID document with an optional live selfie for face matching + liveness.
     *
     * @param UploadedFile $idDocument  The uploaded ID document
     * @param UploadedFile|null $selfie The captured live selfie (from camera)
     * @param string|null $clientRef    Optional client reference (e.g. user ID)
     * @return array Verification result
     */
    public function verifyDocument(UploadedFile $idDocument, ?UploadedFile $selfie = null, ?string $clientRef = null): array
    {
        try {
            // Validate file types
            $this->validateFile($idDocument, 'document');
            if ($selfie) {
                $this->validateFile($selfie, 'selfie');
            }

            // Store files temporarily
            $documentPath = $this->storeTemporaryFile($idDocument);
            $selfiePath = $selfie ? $this->storeTemporaryFile($selfie) : null;

            // Prepare API request
            $response = $this->makeApiRequest($documentPath, $selfiePath, $clientRef);

            // Clean up temporary files
            $this->cleanupTemporaryFile($documentPath);
            if ($selfiePath) {
                $this->cleanupTemporaryFile($selfiePath);
            }

            // Parse and return results
            return $this->parseApiResponse($response);

        } catch (\Exception $e) {
            Log::error('ID Analyzer verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success'  => false,
                'error'    => $e->getMessage(),
                'verified' => false,
            ];
        }
    }

    /**
     * Convert a base64-encoded image string into a temp UploadedFile instance.
     * Used to pass a live selfie (captured from browser camera) to verifyDocument().
     *
     * @param string $base64String  The raw base64 string (may include "data:image/png;base64," prefix)
     * @return UploadedFile
     */
    public function createUploadedFileFromBase64(string $base64String): UploadedFile
    {
        // Strip data URI prefix if present (e.g. "data:image/png;base64,")
        if (str_contains($base64String, ',')) {
            $base64String = substr($base64String, strpos($base64String, ',') + 1);
        }

        $imageData = base64_decode($base64String);

        if ($imageData === false) {
            throw new \InvalidArgumentException('Invalid base64 selfie data received.');
        }

        // Write to a temp file
        $tmpPath = sys_get_temp_dir() . '/live_selfie_' . uniqid() . '.png';
        file_put_contents($tmpPath, $imageData);

        return new UploadedFile(
            path: $tmpPath,
            originalName: 'live_selfie.png',
            mimeType: 'image/png',
            error: UPLOAD_ERR_OK,
            test: true // bypass is_uploaded_file() check
        );
    }

    /**
     * Validate uploaded file
     */
    private function validateFile(UploadedFile $file, string $type = 'document'): void
    {
        $allowedMimes = $type === 'selfie'
            ? ['image/jpeg', 'image/jpg', 'image/png']
            : ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \InvalidArgumentException("Invalid {$type} file type. Allowed: " . implode(', ', $allowedMimes));
        }

        // File size limit: 10MB
        if ($file->getSize() > 10 * 1024 * 1024) {
            throw new \InvalidArgumentException("{$type} file size must be less than 10MB");
        }
    }

    /**
     * Store file temporarily for API upload
     */
    private function storeTemporaryFile(UploadedFile $file): string
    {
        $fileName = 'temp_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('temp/id-verification', $fileName, 'local');
        return Storage::disk('local')->path($path);
    }

    /**
     * Clean up temporary file
     */
    private function cleanupTemporaryFile(string $path): void
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Make API request to ID Analyzer
     */
    private function makeApiRequest(string $documentPath, ?string $selfiePath = null, ?string $clientRef = null): array
    {
        $multipart = [
            [
                'name'     => 'apikey',
                'contents' => $this->apiKey
            ],
            [
                'name'     => 'file',
                'contents' => fopen($documentPath, 'r'),
                'filename' => basename($documentPath)
            ],
            [
                'name'     => 'accuracy',
                'contents' => '2' // High accuracy mode
            ],
            [
                'name'     => 'type',
                'contents' => implode(',', $this->acceptedDocuments)
            ],
        ];

        // Add optional profile ID
        if ($this->profileId) {
            $multipart[] = [
                'name'     => 'profile',
                'contents' => $this->profileId
            ];
        }

        // Add optional client reference
        if ($clientRef) {
            $multipart[] = [
                'name'     => 'client_ref',
                'contents' => $clientRef
            ];
        }

        // FORCE SAVE: Add parameters to ensure transaction is saved
        $multipart[] = ['name' => 'vault_save', 'contents' => '1'];
        $multipart[] = ['name' => 'save_data',  'contents' => '1'];

        // DEBUG: Log the request payload (omit file content)
        Log::info('ID Analyzer Request Payload:', array_map(function ($item) {
            return [
                'name'     => $item['name'],
                'contents' => in_array($item['name'], ['file', 'face']) ? 'FILE_RESOURCE' : $item['contents']
            ];
        }, $multipart));

        // Add live selfie for face matching + liveness detection
        if ($selfiePath) {
            $multipart[] = [
                'name'     => 'face',
                'contents' => fopen($selfiePath, 'r'),
                'filename' => basename($selfiePath)
            ];
            // biometric: 1  => enables face match between ID photo and selfie
            // liveness: 1   => enables anti-spoofing liveness detection on the selfie
            $multipart[] = ['name' => 'biometric', 'contents' => '1'];
            $multipart[] = ['name' => 'liveness',  'contents' => '1'];
        }

        $response = Http::timeout(90)
            ->withOptions([
                'verify' => false, // Disable SSL verification for local development
            ])
            ->asMultipart()
            ->post($this->baseUrl, $multipart);

        if (!$response->successful()) {
            throw new \Exception('ID Analyzer API request failed: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Parse API response
     */
    private function parseApiResponse(array $response): array
    {
        // Check if the API returned an error
        if (isset($response['error'])) {
            return [
                'success'    => false,
                'verified'   => false,
                'error'      => $response['error']['message'] ?? 'Unknown API error',
                'error_code' => $response['error']['code'] ?? null,
            ];
        }

        // Extract verification data
        $result         = $response['result'] ?? [];
        $authentication = $result['authentication'] ?? [];
        $faceMatch      = $response['face'] ?? [];

        // Calculate document score
        $documentScore      = $authentication['score'] ?? 0;
        $documentScoreFloat = (float)($documentScore / 100);

        // Calculate face match score
        $faceScore      = !empty($faceMatch) ? ($faceMatch['confidence'] ?? null) : null;
        $faceScoreFloat = $faceScore !== null ? (float)$faceScore : null;

        // Calculate liveness score
        // IDAnalyzer returns face.liveness as a float 0-1
        $livenessScore  = isset($faceMatch['liveness']) ? (float)$faceMatch['liveness'] : null;
        $livenessPassed = $livenessScore !== null ? ($livenessScore >= 0.6) : true; // true if not checked

        // Document is "authenticated" if documentType present + score >= threshold (or score = 0 = unsupported check)
        $documentVerified = !empty($result['documentType']) && ($documentScore === 0 || $documentScoreFloat >= $this->confidenceThreshold);

        // Face is verified if face match >= threshold
        $faceVerified = $faceScoreFloat === null ? true : ($faceScoreFloat >= $this->confidenceThreshold);

        // Overall: all three conditions must pass
        $verified = $documentVerified && $faceVerified && $livenessPassed;

        return [
            'success'           => true,
            'verified'          => $verified,
            'document_type'     => $result['documentType'] ?? null,
            'document_number'   => $result['documentNumber'] ?? null,
            'full_name'         => trim(($result['firstName'] ?? '') . ' ' . ($result['lastName'] ?? '')),
            'date_of_birth'     => $result['dob'] ?? null,
            'expiry_date'       => $result['expiry'] ?? null,
            'issuing_country'   => $result['country'] ?? null,
            'confidence_score'  => $documentScore / 100,
            'face_match_score'  => $faceScore,
            'liveness_score'    => $livenessScore,
            'liveness_passed'   => $livenessPassed,
            'authentication_score' => $documentScore,
            'warnings'          => $authentication['warnings'] ?? [],
            'extracted_data'    => $result,
            'timestamp'         => now()->toIso8601String(),
        ];
    }

    /**
     * Store verification result permanently
     */
    public function storeVerificationDocument(UploadedFile $document, string $userId): string
    {
        $fileName = "user_{$userId}_id_" . time() . '.' . $document->getClientOriginalExtension();
        $path = $document->storeAs('organizer-verification/ids', $fileName, 'public');
        return $path;
    }

    /**
     * Store selfie permanently
     */
    public function storeVerificationSelfie(UploadedFile $selfie, string $userId): string
    {
        $fileName = "user_{$userId}_selfie_" . time() . '.' . $selfie->getClientOriginalExtension();
        $path = $selfie->storeAs('organizer-verification/selfies', $fileName, 'public');
        return $path;
    }

    /**
     * Get human-readable verification status message
     */
    public function getVerificationStatusMessage(array $result): string
    {
        if (!$result['success']) {
            return $result['error'] ?? 'Verification failed';
        }

        if ($result['verified']) {
            return 'ID successfully verified';
        }

        $reasons = [];

        $documentScore      = $result['authentication_score'] ?? 0;
        $documentScoreFloat = (float)($documentScore / 100);
        $faceScore          = $result['face_match_score'] ?? null;
        $faceScoreFloat     = $faceScore !== null ? (float)$faceScore : null;
        $livenessScore      = $result['liveness_score'] ?? null;

        // Liveness failure — checked first (most important for security)
        if ($livenessScore !== null && $livenessScore < 0.6) {
            $reasons[] = 'Live selfie did not pass liveness check (possible spoofing detected). Please take a fresh selfie directly from your camera';
        }

        // Face mismatch
        if ($faceScoreFloat !== null && $faceScoreFloat < $this->confidenceThreshold) {
            $reasons[] = 'Your face does not match the photo on your ID document';
        }

        // Document unreadable
        if (empty($result['document_type'])) {
            $reasons[] = 'ID document could not be read — please ensure the photo is clear, well-lit, and not cropped';
        } elseif ($documentScore > 0 && $documentScoreFloat < $this->confidenceThreshold) {
            $reasons[] = 'ID document authenticity could not be confirmed';
        }

        if (empty($reasons) && !empty($result['warnings'])) {
            $reasons[] = 'Document quality issues detected (glare, blur, or obstruction)';
        }

        return !empty($reasons)
            ? 'Verification failed: ' . implode('. ', $reasons)
            : 'Verification failed: Please ensure your ID and selfie are clear and try again';
    }
}
