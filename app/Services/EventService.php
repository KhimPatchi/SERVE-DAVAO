<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use App\Models\EventVolunteer;

class EventService
{
    public function createEvent(array $data, User $organizer): Event
    {
        // Verify user can create events (verified organizers or admins only)
        if (!$organizer->canCreateEvents()) {
            throw new \Exception('You are not authorized to create events. Please become a verified organizer first.');
        }

        // Combine date and time
        $eventDateTime = $data['date'] . ' ' . $data['time'];

        // Verified organizers and admins can create active events immediately
        $status = 'active';

        // FIX: Get fresh user data to ensure we have the correct name
        $freshOrganizer = User::find($organizer->id);

        $event = Event::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'date' => $eventDateTime,
            'location' => $data['location'],
            'required_volunteers' => $data['required_volunteers'],
            'skills_required' => $data['skills_required'] ?? null,
            'organizer_id' => $freshOrganizer->id,
            'organizer_name' => $freshOrganizer->name, // This will now work
            'current_volunteers' => 0,
            'status' => $status,
        ]);

        return $event;
    }
             // ADD THIS METHOD TO FIX THE DASHBOARD ERROR
    public function getUserVolunteerStats(User $user): array
    {
        // Use the User model's existing stats methods
        if ($user->isVerifiedOrganizer() || $user->isAdmin()) {
            return $user->getOrganizerStats();
        } else {
            return $user->getVolunteerStats();
        }
    }
    public function getAvailableEvents()
    {
        return Event::with('organizer')
            ->active() // Use the new scope
            ->whereColumn('current_volunteers', '<', 'required_volunteers')
            ->orderBy('date', 'asc')
            ->paginate(10);
    }

    public function getAllEventsForPublic()
    {
        return Event::with('organizer')
            ->active() // Use the new scope - only shows future active events
            ->orderBy('date', 'asc')
            ->paginate(10);
    }

    public function getAllEventsForAdmin()
    {
        return Event::with('organizer')
            ->latest()
            ->paginate(15);
    }

    public function getCompletedEvents()
    {
        return Event::with('organizer')
            ->completed() // Use the new completed scope
            ->latest()
            ->paginate(10);
    }

    // ... rest of your existing methods remain the same ...

    public function getEventStatistics(): array
    {
        return [
            'total_events' => Event::count(),
            'active_events' => Event::active()->count(),
            'pending_events' => Event::pending()->count(),
            'completed_events' => Event::completed()->count(),
            'cancelled_events' => Event::where('status', 'rejected')->count(),
        ];
    }   

    // NEW: Manual status update method
    public function updateEventStatus(Event $event): bool
    {
        return $event->updateStatusBasedOnDates();
    }
}