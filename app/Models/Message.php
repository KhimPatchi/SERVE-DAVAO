<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
        'is_system_message',
        'attachment',
        'attachment_type',
    ];

    protected $casts = [
        'is_system_message' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = ['attachment_url', 'formatted_time'];

    // ... (relationships remain the same)

    /**
     * Get attachment URL
     */
    public function getAttachmentUrlAttribute()
    {
        if (!$this->attachment) {
            return null;
        }

        if (str_starts_with($this->attachment, 'http')) {
            return $this->attachment;
        }

        return asset('storage/' . $this->attachment);
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the conversation this message belongs to
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the sender of this message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ==================== SCOPES ====================

    /**
     * Scope to get unread messages for a user
     */
    public function scopeUnreadBy($query, User $user, $lastReadAt = null)
    {
        return $query->where('user_id', '!=', $user->id)
            ->when($lastReadAt, function ($q) use ($lastReadAt) {
                $q->where('created_at', '>', $lastReadAt);
            });
    }

    /**
     * Scope to exclude system messages
     */
    public function scopeUserMessages($query)
    {
        return $query->where('is_system_message', false);
    }

    /**
     * Scope to get only system messages
     */
    public function scopeSystemMessages($query)
    {
        return $query->where('is_system_message', true);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if message belongs to user
     */
    public function belongsToUser(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    /**
     * Get formatted time for display
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at ? $this->created_at->diffForHumans() : 'Just now';
    }
}
