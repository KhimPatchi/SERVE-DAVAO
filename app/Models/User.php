<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'google_id', 'avatar', 'role', 'google_avatar',
        'preferences', 'interests', 'experience_level', 'availability',
        'latitude', 'longitude', 'preferred_radius', 'primary_priority'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['avatar_url'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'latitude'          => 'float',
        'longitude'         => 'float',
        'preferred_radius'  => 'float',
    ];

    // RELATIONSHIPS
    public function organizedEvents()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public function volunteeringEvents()
    {
        return $this->belongsToMany(Event::class, 'event_volunteers', 'volunteer_id', 'event_id')
                    ->wherePivot('status', 'registered')
                    ->withPivot('status', 'hours_volunteered')
                    ->withTimestamps();
    }

    // NEW: Unfiltered relationship for history
    public function allVolunteeringEvents()
    {
        return $this->belongsToMany(Event::class, 'event_volunteers', 'volunteer_id', 'event_id')
                    ->withPivot('status', 'hours_volunteered')
                    ->withTimestamps();
    }

    public function volunteerRegistrations()
    {
        return $this->hasMany(EventVolunteer::class, 'volunteer_id');
    }

    public function audits()
    {
        return $this->hasMany(Audit::class);
    }

    // MESSAGING RELATIONSHIPS
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_participants')
            ->withPivot('last_read_at', 'is_admin')
            ->withTimestamps()
            ->orderByDesc('updated_at');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class);
    }

    // NEW: Organizer Verification Relationship
    public function organizerVerification()
    {
        return $this->hasOne(OrganizerVerification::class);
    }

    // CLEAN ROLE SYSTEM - ONLY 2 ROLES: Organizer (verified) and Volunteer
    // No admin role needed - verification is automated
    public function isAdmin(): bool
    {
        return false;
    }

    // UPDATED: Organizer status based on verification system
    public function isOrganizer(): bool
    {
        return $this->isVerifiedOrganizer() || $this->hasPendingVerification();
    }

    public function isVerifiedOrganizer(): bool
    {
        return $this->organizerVerification()->where('status', 'approved')->exists();
    }

    public function hasPendingVerification(): bool
    {
        return $this->organizerVerification()->where('status', 'pending')->exists();
    }

    public function hasRejectedVerification(): bool
    {
        return $this->organizerVerification()->where('status', 'rejected')->exists();
    }

    public function canCreateEvents(): bool
    {
        return $this->isVerifiedOrganizer();
    }

    // CLEAN STATS METHODS
    public function getVolunteerStats(): array
    {
        // Count ALL events this volunteer has joined (registered OR already attended)
        $joinedEventIds = $this->volunteerRegistrations()
            ->whereIn('status', ['registered', 'attended'])
            ->pluck('event_id');

        // Count upcoming events (still registered, active, in the future)
        $upcomingEventsCount = Event::whereIn('id',
                $this->volunteerRegistrations()
                    ->where('status', 'registered')
                    ->pluck('event_id')
            )
            ->where('status', 'active')
            ->where('date', '>=', now())
            ->count();

        // Count hours for all attended events whose date has already passed.
        // This works even if the organizer forgot to officially "end" the event,
        // so the event status may still be "active" instead of "completed".
        $certifiedHours = $this->volunteerRegistrations()
            ->where('status', 'attended')
            ->whereHas('event', fn($q) => $q->where('date', '<', now()))
            ->sum('hours_volunteered');

        return [
            'total_volunteers' => $joinedEventIds->count(),
            'upcoming_events'  => $upcomingEventsCount,
            'total_hours'      => $certifiedHours,
        ];
    }

   public function getOrganizerStats(): array
        {
            $totalVolunteers = EventVolunteer::whereIn('event_id', $this->organizedEvents()->pluck('id'))
                ->where('status', 'registered')
                ->count();

            $upcomingEvents = $this->organizedEvents()
                ->where('status', 'active')
                ->where('date', '>=', now())
                ->count();

            return [
                'total_volunteers' => $totalVolunteers,
                'upcoming_events' => $upcomingEvents,
                'total_hours' => 0, // Organizers don't track personal hours
             ];
         }

    // MESSAGING HELPER METHODS
    public function getTotalUnreadMessagesCount(): int
    {
        $count = 0;
        foreach ($this->conversations as $conversation) {
            $count += $conversation->getUnreadCount($this);
        }
        return $count;
    }

    public function getDirectConversationWith(User $otherUser): ?Conversation
    {
        return $this->conversations()
            ->where('type', 'direct')
            ->whereHas('participants', function ($query) use ($otherUser) {
                $query->where('user_id', $otherUser->id);
            })
            ->first();
    }

    /**
     * Get the user's avatar URL (Accessor: $user->avatar_url)
     */
    public function getAvatarUrlAttribute(): string
    {
        $avatar = $this->avatar ?? $this->google_avatar;

        if (!$avatar) {
            return asset('images/default-avatar.png');
        }

        if (str_starts_with($avatar, 'http')) {
            return $avatar;
        }

        $path = str_starts_with($avatar, 'storage') ? $avatar : 'storage/' . $avatar;
        return asset($path);
    }
}