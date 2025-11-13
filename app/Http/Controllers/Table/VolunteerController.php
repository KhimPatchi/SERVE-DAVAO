<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\EventVolunteer; // ← ADD THIS IMPORT

class VolunteerController extends Controller
{
    public function __construct(
        private EventService $eventService
    ) {}

    public function index()
    {
        $events = $this->eventService->getAvailableEvents();
        return response()
            ->view('volunteers.index', compact('events'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function myEvents()
{
    $events = Auth::user()->volunteeringEvents()
                ->with('organizer')
                ->orderBy('date', 'asc')
                ->paginate(10);

    // Calculate the statistics
    $upcomingCount = Auth::user()->volunteeringEvents()
                        ->where('date', '>', now()->endOfDay())
                        ->count();
    
    $completedCount = Auth::user()->volunteeringEvents()
                         ->where('date', '<', now()->startOfDay())
                         ->count();

    $totalHours = Auth::user()->volunteerRegistrations
                    ->where('status', 'registered')
                    ->sum('hours_volunteered');

    return response()
        ->view('volunteers.my-events', compact('events', 'upcomingCount', 'completedCount', 'totalHours'))
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
}

    public function organizedEvents()
    {
        $user = Auth::user();
        
        // Get ALL events (keep this for backward compatibility)
        $events = $user->organizedEvents()
            ->withCount('volunteers')
            ->orderBy('date', 'desc')
            ->paginate(12);

        // Separate current events (active + upcoming)
        $currentEvents = $user->organizedEvents()
            ->withCount('volunteers')
            ->where('status', 'active')
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->get();

        // Separate past events (completed or past dates)
        $pastEvents = $user->organizedEvents()
            ->withCount('volunteers')
            ->where(function($query) {
                $query->where('status', 'completed')
                      ->orWhere('date', '<', now());
            })
            ->orderBy('date', 'desc')
            ->get();

        // Statistics
        $currentEventsCount = $currentEvents->count();
        $completedEventsCount = $pastEvents->count();
        
        $totalVolunteersCount = EventVolunteer::whereIn('event_id', $user->organizedEvents()->pluck('id'))
            ->where('status', 'registered')
            ->count();

        // History statistics
        $totalEventsCount = $user->organizedEvents()->count();
        $successfulEventsCount = $user->organizedEvents()
            ->where('status', 'completed')
            ->count();
            
        $totalVolunteersHistory = EventVolunteer::whereIn('event_id', $user->organizedEvents()->pluck('id'))
            ->where('status', 'registered')
            ->count();

        return view('volunteers.organized-events', compact(
            'events', // ← KEEP THIS for pagination compatibility
            'currentEvents', 
            'pastEvents',
            'currentEventsCount',
            'completedEventsCount',
            'totalVolunteersCount',
            'totalEventsCount',
            'successfulEventsCount',
            'totalVolunteersHistory'
        ));
    }

    public function eventVolunteers(Event $event)
    {
        if ($event->organizer_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access. You can only manage your own events.');
        }

        // FIXED: Use the correct relationship name "volunteers" instead of "eventVolunteers"
        $volunteers = $event->volunteers()  // ← CHANGED FROM eventVolunteers() to volunteers()
                            ->with('volunteer') // ← Eager load the user relationship for avatars
                            ->where('status', 'registered')
                            ->get();
        
        return response()
            ->view('volunteers.event-volunteers', compact('event', 'volunteers'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}