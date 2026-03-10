<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'event_id',
        'title',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get all participants in this conversation
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withPivot('last_read_at', 'is_admin')
            ->withTimestamps();
    }

    /**
     * Get all messages in this conversation
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the latest message in this conversation
     */
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Get the event (for event group conversations)
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // ==================== STATIC FACTORY METHODS ====================

    /**
     * Create a direct conversation between two users
     */
    public static function createDirectConversation(User $user1, User $user2): self
    {
        // Check if conversation already exists
        $existingConversation = self::whereHas('participants', function ($query) use ($user1) {
            $query->where('user_id', $user1->id);
        })->whereHas('participants', function ($query) use ($user2) {
            $query->where('user_id', $user2->id);
        })->where('type', 'direct')->first();

        if ($existingConversation) {
            return $existingConversation;
        }

        // Create new conversation
        $conversation = self::create([
            'type' => 'direct',
        ]);

        // Ensure we have valid IDs before attaching
        if ($user1->id && $user2->id) {
            $conversation->participants()->syncWithoutDetaching([
                $user1->id => ['last_read_at' => now()],
                $user2->id => ['last_read_at' => now()],
            ]);
        }

        return $conversation;
    }

    /**
     * Create an event group conversation
     */
    public static function createEventConversation(Event $event): self
    {
        // Check if conversation already exists
        $existing = self::where('event_id', $event->id)
            ->where('type', 'event_group')
            ->first();

        if ($existing) {
            return $existing;
        }

        // Create conversation
        $conversation = self::create([
            'type' => 'event_group',
            'event_id' => $event->id,
            'title' => $event->title . ' - Group Chat',
        ]);

        // Add organizer
        if ($event->organizer) {
            $conversation->addParticipant($event->organizer, true);
        }

        // Add all registered volunteers
        $volunteers = $event->registeredVolunteers()->get();
        foreach ($volunteers as $volunteer) {
            $conversation->addParticipant($volunteer);
        }

        return $conversation;
    }

    // ==================== INSTANCE METHODS ====================

    /**
     * Add a participant to the conversation
     */
    public function addParticipant(User $user, bool $isAdmin = false): void
    {
        if (!$this->isParticipant($user)) {
            $this->participants()->attach($user->id, [
                'is_admin' => $isAdmin,
                'last_read_at' => now(),
            ]);
        }
    }

    /**
     * Remove a participant from the conversation
     */
    public function removeParticipant(User $user): void
    {
        $this->participants()->detach($user->id);
    }

    /**
     * Check if user is a participant
     */
    public function isParticipant(User $user): bool
    {
        return $this->participants()->where('user_id', $user->id)->exists();
    }

    /**
     * Mark conversation as read by user
     */
    public function markAsReadBy(User $user): void
    {
        $this->participants()->updateExistingPivot($user->id, [
            'last_read_at' => now(),
        ]);
    }

    /**
     * Get unread message count for a user
     */
    public function getUnreadCount(User $user): int
    {
        $participant = $this->participants()->where('user_id', $user->id)->first();
        
        if (!$participant) {
            return 0;
        }

        $lastReadAt = $participant->pivot->last_read_at;

        return $this->messages()
            ->where('user_id', '!=', $user->id) // Don't count own messages
            ->where(function ($query) use ($lastReadAt) {
                if ($lastReadAt) {
                    $query->where('created_at', '>', $lastReadAt);
                }
            })
            ->count();
    }

    /**
     * Get the other participant in a direct conversation
     */
    public function getOtherParticipant(User $currentUser): ?User
    {
        if ($this->type !== 'direct') {
            return null;
        }

        return $this->participants()
            ->where('user_id', '!=', $currentUser->id)
            ->first();
    }

    /**
     * Get conversation display name for a user
     */
    public function getDisplayName(User $currentUser): string
    {
        if ($this->type === 'event_group') {
            // Strip the ' - Group Chat' suffix if present, show clean event name
            $title = $this->title ?? 'Group Chat';
            return str_replace(' - Group Chat', '', $title);
        }

        $otherParticipant = $this->getOtherParticipant($currentUser);
        return $otherParticipant ? $otherParticipant->name : 'Unknown User';
    }

    /**
     * Get conversation avatar URL
     */
    public function getAvatarUrl(User $currentUser): string
    {
        if ($this->type === 'event_group' && $this->event) {
            if ($this->event->image) {
                // Check if it's already a full URL or needs storage prefix
                if (str_starts_with($this->event->image, 'http')) return $this->event->image;
                return asset(str_starts_with($this->event->image, 'storage') ? $this->event->image : 'storage/' . $this->event->image);
            }
            return asset('images/default-event.svg');
        }

        $otherParticipant = $this->getOtherParticipant($currentUser);
        if (!$otherParticipant) return asset('images/default-avatar.svg');

        $avatar = $otherParticipant->avatar ?? $otherParticipant->google_avatar;
        
        if (!$avatar) return asset('images/default-avatar.svg');

        // If it's a full URL (like from Google or external), return it
        if (str_starts_with($avatar, 'http')) return $avatar;

        // Ensure it has storage prefix and asset helper
        $path = str_starts_with($avatar, 'storage') ? $avatar : 'storage/' . $avatar;
        return asset($path);
    }
}
