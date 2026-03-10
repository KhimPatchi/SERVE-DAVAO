<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizerVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'applicant_name',
        'organization_name',
        'organization_type',
        'identification_number',
        'identification_document_path',
        'selfie_path', // For face matching
        'phone',
        'address',
        'status',
        'rejection_reason',
        'approved_at',
        'rejected_at',
        // ID Analyzer fields
        'document_type',
        'verification_score',
        'face_match_score',
        'issuing_country',
        'verification_data',
        'verified_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'verified_at' => 'datetime',
        'verification_data' => 'array', // Cast JSON to array
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // This ensures new records get applicant_name automatically
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($verification) {
            if (empty($verification->applicant_name) && $verification->user) {
                $verification->applicant_name = $verification->user->name;
            }
        });
    }

    // Scope for pending applications
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for approved applications
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Check if user has pending verification
    public static function userHasPendingVerification($userId)
    {
        return self::where('user_id', $userId)->where('status', 'pending')->exists();
    }

    // Check if user is verified organizer
    public static function userIsVerifiedOrganizer($userId)
    {
        return self::where('user_id', $userId)->where('status', 'approved')->exists();
    }

    // Get latest verification for user
    public static function getLatestForUser($userId)
    {
        return self::where('user_id', $userId)->latest()->first();
    }
}