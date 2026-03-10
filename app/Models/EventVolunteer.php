<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventVolunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'volunteer_id',
        'status',
        'hours_volunteered',
        'check_in_time',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
    ];

    protected $attributes = [
        'status'            => 'registered',
        'hours_volunteered' => 0,
    ];

    // ─── Relationships ──────────────────────────────────────────────────────

    public function event(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function volunteer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    // ─── Status Helpers ─────────────────────────────────────────────────────

    public function isRegistered(): bool
    {
        return $this->status === 'registered';
    }

    public function isAttended(): bool
    {
        return $this->status === 'attended';
    }

    public function isNoShow(): bool
    {
        return $this->status === 'no-show';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    // ─── Scopes ─────────────────────────────────────────────────────────────

    public function scopeRegistered($query)
    {
        return $query->where('status', 'registered');
    }

    public function scopeAttended($query)
    {
        return $query->where('status', 'attended');
    }

    public function scopeNoShow($query)
    {
        return $query->where('status', 'no-show');
    }
}