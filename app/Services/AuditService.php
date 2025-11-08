<?php
// app/Services/AuditService.php

namespace App\Services;

use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public static function log($action, $description, $auditableType, $auditableId, $metadata = null)
    {
        $auditData = [
            'action' => $action,
            'description' => $description,
            'auditable_type' => $auditableType,
            'auditable_id' => $auditableId,
            'user_id' => Auth::id(),
            'metadata' => $metadata,
        ];

        // Only add these if columns exist (safe approach)
        try {
            $auditData['ip_address'] = Request::ip();
            $auditData['user_agent'] = Request::userAgent();
        } catch (\Exception $e) {
            // Columns might not exist yet, skip them
        }

        return Audit::create($auditData);
    }

    // Log organizer approval
    public static function logOrganizerApproval($user, $organizerVerification)
    {
        return self::log(
            'organizer_approved',
            "Admin approved organizer verification for {$user->email}",
            'App\Models\OrganizerVerification',
            $organizerVerification->id,
            [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_name' => $user->name,
                'old_status' => $organizerVerification->getOriginal('status'),
                'new_status' => 'approved',
                'organization_name' => $organizerVerification->organization_name,
                'verification_id' => $organizerVerification->id
            ]
        );
    }

    // Log organizer rejection
    public static function logOrganizerRejection($user, $organizerVerification, $reason = null)
    {
        return self::log(
            'organizer_rejected',
            "Admin rejected organizer verification for {$user->email}" . ($reason ? " - Reason: {$reason}" : ""),
            'App\Models\OrganizerVerification',
            $organizerVerification->id,
            [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_name' => $user->name,
                'old_status' => $organizerVerification->getOriginal('status'),
                'new_status' => 'rejected',
                'reason' => $reason,
                'organization_name' => $organizerVerification->organization_name,
                'verification_id' => $organizerVerification->id
            ]
        );
    }
}