<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use App\Models\EventVolunteer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class EventService
{
  public function createEvent(array $data, User $organizer): Event
{
    // Verify user can create events (verified organizers only)
    if (!$organizer->canCreateEvents()) {
        throw new \Exception('You are not authorized to create events. Please become a verified organizer first.');
    }

    // Handle image upload if present
    $imagePath = null;
    if (isset($data['event_image']) && $data['event_image']) {
        // Store the image in public/storage/events directory
        $imagePath = $data['event_image']->store('events', 'public');
    }

    // Date and time are now already combined from the Request
    // $data['date'] now contains the full datetime string

    // All events from verified organizers are active immediately
    $status = 'active';

    // FIX: Get fresh user data to ensure we have the correct name
    $freshOrganizer = User::find($organizer->id);

    $event = Event::create([
        'title'               => $data['title'],
        'description'         => $data['description'],
        'image'               => $imagePath,
        'date'                => $data['date'],
        'end_time'            => $data['end_time'] ?? null,
        'location'            => $data['location'],
        'latitude'            => $data['latitude'] ?? null,
        'longitude'           => $data['longitude'] ?? null,
        'target_radius'       => $data['target_radius'] ?? null,
        'required_volunteers' => $data['required_volunteers'],
        'skills_preferred'    => $data['skills_preferred'] ?? null,
        'organizer_id'        => $freshOrganizer->id,
        'organizer_name'      => $freshOrganizer->name,
        'current_volunteers'  => 0,
        'status'              => $status,
    ]);

    // Invalidate the TF-IDF corpus cache so new event keywords are indexed
    Cache::forget('tfidf_idf_dictionary');

    return $event;
}

public function updateEvent(Event $event, array $data): bool
{
    // Handle image upload if a new one is provided
    if (isset($data['event_image']) && $data['event_image']) {
        // Delete old image if it exists
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        // Store the new image
        $data['image'] = $data['event_image']->store('events', 'public');
    }

    // Filter out the event_image from the data array as it's not a column
    unset($data['event_image']);

    return $event->update($data);
}
    public function getUserVolunteerStats(User $user): array
    {
        // Use the User model's existing stats methods
        if ($user->isVerifiedOrganizer()) {
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

    public function getAllAvailableEventsCollection()
    {
        return Event::with('organizer')
            ->active() // Use the new scope
            ->whereColumn('current_volunteers', '<', 'required_volunteers')
            ->orderBy('date', 'asc')
            ->get();
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