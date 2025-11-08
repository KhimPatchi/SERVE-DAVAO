<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizerVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'applicant_name', // ADD THIS LINE
        'organization_name',
        'organization_type',
        'identification_number',
        'identification_document_path',
        'phone',
        'address',
        'status',
        'rejection_reason',
        'approved_at',
        'rejected_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
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