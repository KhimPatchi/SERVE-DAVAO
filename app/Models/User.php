<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'google_id', 'avatar', 'role','google_avatar'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
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

    public function volunteerRegistrations()
    {
        return $this->hasMany(EventVolunteer::class, 'volunteer_id');
    }

    public function audits()
    {
        return $this->hasMany(Audit::class);
    }

    // NEW: Organizer Verification Relationship
    public function organizerVerification()
    {
        return $this->hasOne(OrganizerVerification::class);
    }

    // CLEAN ROLE SYSTEM - ONLY 2 ROLES
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
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
        return $this->isAdmin() || $this->isVerifiedOrganizer();
    }

    // CLEAN STATS METHODS
    public function getVolunteerStats(): array
    {
        // Get registered event IDs for this user
        $registeredEventIds = $this->volunteerRegistrations()
            ->where('status', 'registered')
            ->pluck('event_id');

        // Count active upcoming events
        $upcomingEventsCount = Event::whereIn('id', $registeredEventIds)
            ->where('status', 'active')
            ->where('date', '>=', now())
            ->count();

        return [
            'total_volunteers' => $registeredEventIds->count(),
            'upcoming_events' => $upcomingEventsCount,
            'total_hours' => $this->volunteerRegistrations()->sum('hours_volunteered'),
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
}