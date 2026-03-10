<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'location',
        'votes',
        'status',
        'organizer_notes',
        'suggested_after_event_id',
    ];

    protected $casts = [
        'votes'                    => 'integer',
        'suggested_after_event_id' => 'integer',
    ];

    // RELATIONSHIPS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function suggestionVotes()
    {
        return $this->hasMany(SuggestionVote::class, 'suggestion_id');
    }

    public function voters()
    {
        return $this->belongsToMany(User::class, 'suggestion_votes', 'suggestion_id', 'user_id')
            ->withTimestamps();
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'rejected');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePopular($query, $minVotes = 5)
    {
        return $query->where('votes', '>=', $minVotes)
            ->orderBy('votes', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // HELPER METHODS
    public function hasVoted($userId)
    {
        return $this->suggestionVotes()
            ->where('user_id', $userId)
            ->exists();
    }

    public function toggleVote($userId)
    {
        if ($this->hasVoted($userId)) {
            // Remove vote
            $this->suggestionVotes()->where('user_id', $userId)->delete();
            $this->decrement('votes');
            return 'unvoted';
        } else {
            // Add vote
            $this->suggestionVotes()->create(['user_id' => $userId]);
            $this->increment('votes');
            return 'voted';
        }
    }
}
