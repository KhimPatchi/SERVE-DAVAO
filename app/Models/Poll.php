<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'status', 'closes_at'];

    protected $casts = [
        'closes_at' => 'datetime',
    ];

    // RELATIONSHIPS
    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function options()
    {
        return $this->hasMany(PollOption::class);
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    // HELPERS
    public function hasVoted($userId): bool
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    public function getUserVote($userId): ?PollVote
    {
        return $this->votes()->where('user_id', $userId)->first();
    }

    public function totalVotes(): int
    {
        return $this->votes()->count();
    }

    public function isActive(): bool
    {
        if ($this->status === 'closed') return false;
        if ($this->closes_at && $this->closes_at->isPast()) return false;
        return true;
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where(fn($q) => $q->whereNull('closes_at')->orWhere('closes_at', '>', now()));
    }

    public function scopeClosed($query)
    {
        return $query->where(fn($q) =>
            $q->where('status', 'closed')
              ->orWhere('closes_at', '<=', now())
        );
    }
}
