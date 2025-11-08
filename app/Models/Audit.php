<?php
// app/Models/Audit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'action', 
        'description', 
        'auditable_type', 
        'auditable_id', 
        'user_id', 
        'metadata',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function auditable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Specific scopes for organizer approvals
    public function scopeOrganizerApprovals($query)
    {
        return $query->where('action', 'organizer_approved');
    }

    public function scopeOrganizerRejections($query)
    {
        return $query->where('action', 'organizer_rejected');
    }

    public function scopeUserVerifications($query)
    {
        return $query->where('action', 'user_verified');
    }

    public function scopeAdminActions($query)
    {
        return $query->where('auditable_type', User::class)
                    ->orWhere('auditable_type', 'App\Models\OrganizerVerification');
    }
}