<?php

namespace App\Services;

use App\Models\User;
use App\Models\OrganizerVerification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OrganizerVerificationService
{
    public function handleFileUpload(UploadedFile $file): string
    {
        $fileName = 'verification_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('verification_documents', $fileName, 'public');
        
        Log::info('File uploaded for organizer verification', ['path' => $path]);
        return $path;
    }

    public function createVerification(User $user, array $validated, string $documentPath): OrganizerVerification
    {
        Log::info('Creating organizer verification', [
            'user_id' => $user->id,
            'data' => $validated
        ]);

        $verification = OrganizerVerification::create([
            'user_id' => $user->id,
            'organization_name' => $validated['organization_name'],
            'organization_type' => $validated['organization_type'],
            'identification_number' => $validated['identification_number'],
            'identification_document_path' => $documentPath,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'status' => 'pending',
        ]);

        Log::info('Organizer verification created successfully', [
            'verification_id' => $verification->id,
            'user_id' => $user->id
        ]);

        return $verification;
    }

    public function canSubmitVerification(User $user): bool
    {
        return !$user->isVerifiedOrganizer() && 
               !$user->hasPendingVerification();
    }

    public function getVerificationDisplayData(User $user): array
    {
        $verification = $user->organizerVerification;
        
        if (!$verification) {
            return [
                'organization_name' => 'Not provided',
                'organization_type' => 'Not provided',
                'identification_number' => 'Not provided',
                'phone' => 'Not provided',
                'address' => 'Not provided',
                'submitted_at' => 'Not available',
                'document_path' => null,
                'status' => 'not_submitted',
            ];
        }

        Log::info('Retrieving verification display data', [
            'user_id' => $user->id,
            'verification_id' => $verification->id
        ]);
        
        return [
            'organization_name' => $verification->organization_name,
            'organization_type' => $this->formatOrganizationType($verification->organization_type),
            'identification_number' => $verification->identification_number,
            'phone' => $verification->phone,
            'address' => $verification->address,
            'submitted_at' => $verification->created_at->format('M d, Y H:i'),
            'document_path' => $verification->identification_document_path,
            'status' => $verification->status,
            'rejection_reason' => $verification->rejection_reason,
        ];
    }

    public function approveVerification(OrganizerVerification $verification): void
    {
        $verification->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        Log::info('Organizer verification approved', [
            'verification_id' => $verification->id,
            'user_id' => $verification->user_id
        ]);
    }

    public function rejectVerification(OrganizerVerification $verification, string $reason = null): void
    {
        $verification->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_at' => now(),
        ]);

        Log::info('Organizer verification rejected', [
            'verification_id' => $verification->id,
            'user_id' => $verification->user_id,
            'reason' => $reason
        ]);
    }

    public function getPendingVerificationsCount(): int
    {
        return OrganizerVerification::where('status', 'pending')->count();
    }

    public function getUserVerificationStatus(User $user): string
    {
        if ($user->isVerifiedOrganizer()) {
            return 'approved';
        }

        if ($user->hasPendingVerification()) {
            return 'pending';
        }

        if ($user->hasRejectedVerification()) {
            return 'rejected';
        }

        return 'not_submitted';
    }

    private function formatOrganizationType(string $type): string
    {
        $types = [
            'non_profit' => 'Non-Profit Organization',
            'school' => 'School/University',
            'community' => 'Community Group',
            'business' => 'Business',
            'individual' => 'Individual',
            'other' => 'Other',
        ];
        
        return $types[$type] ?? ucfirst(str_replace('_', ' ', $type));
    }
}