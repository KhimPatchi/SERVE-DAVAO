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

        $event = Event::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'date' => $eventDateTime,
            'location' => $data['location'],
            'required_volunteers' => $data['required_volunteers'],
            'skills_required' => $data['skills_required'] ?? null,
            'organizer_id' => $organizer->id,
            'current_volunteers' => 0,
            'status' => $status,
        ]);

        return $event;
    }

    public function getAvailableEvents()
    {
        return Event::with('organizer')
            ->where('status', 'active')
            ->where('date', '>=', now())
            ->whereColumn('current_volunteers', '<', 'required_volunteers')
            ->orderBy('date', 'asc')
            ->paginate(10);
    }

    public function getAllEventsForPublic()
    {
        return Event::with('organizer')
            ->where('status', 'active') // Only show active events to public
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->paginate(10);
    }

    public function getUserVolunteerStats(User $user): array
    {
        if ($user->isVerifiedOrganizer() || $user->isAdmin()) {
            return $user->getOrganizerStats();
        } else {
            return $user->getVolunteerStats();
        }
    }

    public function registerForEvent(Event $event, User $user)
{
    // Prevent organizer from joining their own event
    if ($event->organizer_id === $user->id) {
        throw new \Exception('You cannot join your own event as a volunteer.');
    }

    // Rest of your existing validation logic...
    if ($event->isFull()) {
        throw new \Exception('This event is already full.');
    }

    if ($this->isUserRegistered($event, $user)) {
        throw new \Exception('You are already registered for this event.');
    }
}

    public function unregisterFromEvent(Event $event, User $user)
    {
        $registration = EventVolunteer::where('event_id', $event->id)
            ->where('volunteer_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$registration) {
            throw new \Exception('You are not registered for this event.');
        }

        $registration->update(['status' => 'cancelled']);
        $event->decrement('current_volunteers');
    }

    public function getPendingEvents()
    {
        return Event::with('organizer')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);
    }

    public function getAllEventsForAdmin()
    {
        return Event::with('organizer')
            ->latest()
            ->paginate(15);
    }

    public function approveEvent(Event $event)
    {
        $event->update(['status' => 'active']);
        return $event;
    }

    public function rejectEvent(Event $event)
    {
        $event->update(['status' => 'rejected']);
        return $event;
    }

    public function getEventStatistics(): array
    {
        return [
            'total_events' => Event::count(),
            'active_events' => Event::where('status', 'active')->count(),
            'pending_events' => Event::where('status', 'pending')->count(),
            'completed_events' => Event::where('date', '<', now())->count(),
            'cancelled_events' => Event::where('status', 'rejected')->count(),
        ];
    }
}