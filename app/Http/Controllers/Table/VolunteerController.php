<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\EventVolunteer; // ← ADD THIS IMPORT
use App\Traits\StoresPreviousUrl; // ADD THIS

class VolunteerController extends Controller
{
    use StoresPreviousUrl; // ADD THIS

    public function __construct(
        private EventService $eventService
    ) {}

    public function index()
    {
        $this->storePreviousUrl(); // ADD THIS

        $events = $this->eventService->getAvailableEvents();
        return response()
            ->view('volunteers.index', compact('events'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function myEvents()
    {
        $this->storePreviousUrl();

        $user = Auth::user();
        
        // 1. Upcoming & Active Events
        $events = $user->volunteeringEvents()
            ->with('organizer')
            ->where(function($query) {
                $query->where('events.date', '>', now()->subDay())
                      ->orWhere(function($q) {
                          $q->whereDate('events.date', now()->toDateString());
                      });
            })
            ->where('events.status', 'active')
            ->orderBy('events.date', 'asc')
            ->paginate(10);

        // 2. Attended/History Events (New Feature)
        // Use allVolunteeringEvents to capture 'attended', 'completed', etc.
        $attendedEvents = $user->allVolunteeringEvents()
            ->with(['organizer'])
            ->where(function($query) {
                // Show if event is explicitly completed OR if it's in the past
                $query->where('events.status', 'completed')
                      ->orWhere('events.date', '<', now()->startOfDay());
            })
            // Also include events where user status is explicitly 'attended' or 'completed'
            // regardless of event date/status (e.g. early completion)
            ->orWherePivotIn('status', ['attended', 'completed'])
            ->orderBy('events.date', 'desc')
            ->get();

        // Statistics
        $upcomingCount = $user->volunteeringEvents()
            ->where('events.date', '>', now()->endOfDay())
            ->where('events.status', 'active')
            ->count();

        $ongoingCount = $user->volunteeringEvents()
            ->where('events.status', 'active')
            ->whereDate('events.date', now()->toDateString())
            ->count();

        $completedCount = $user->volunteeringEvents()
            ->where(function($query) {
                $query->where('events.status', 'completed')
                      ->orWhere('events.date', '<', now()->startOfDay());
            })
            ->count();

        $totalHours = $user->volunteerRegistrations
            ->where('status', 'registered')
            ->sum('hours_volunteered');

        return response()
            ->view('volunteers.my-events', compact('events', 'attendedEvents', 'upcomingCount', 'ongoingCount', 'completedCount', 'totalHours'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    
    }

    public function organizedEvents(Request $request)
    {
            $this->storePreviousUrl(); // ADD THIS

        $user = Auth::user();
        $search = $request->input('search');

        // Base query builder for organized events
        $baseQuery = $user->organizedEvents()->withCount('volunteers');

        // Apply search filter if present
        if ($search) {
            $baseQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        // Get ALL events (keep this for backward compatibility)
        // Note: Pagination might be tricky if baseQuery is modified, so we clone or re-query
        // For simplicity, we'll re-apply search to pagination if needed, but the view uses current/past collections mostly.
        // Let's just pass `events` as the paginated result of the search.
        $events = clone $baseQuery;
        $events = $events->orderBy('date', 'desc')->paginate(12);

        // Separate current events (active + upcoming)
        $currentEvents = clone $baseQuery;
        $currentEvents = $currentEvents->where('status', 'active')
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->get();

        // Separate past events (completed or past dates)
        $pastEvents = clone $baseQuery;
        $pastEvents = $pastEvents->where(function($query) {
                $query->where('status', 'completed')
                      ->orWhere('date', '<', now());
            })
            ->orderBy('date', 'desc')
            ->get();

        // Statistics (should probably reflect TOTAL stats, not filtered stats, usually? 
        // Or do we want filtered stats? Usually dashboard stats are global. 
        // Let's keep stats global (unfiltered) to avoid confusion.)
        
        $currentEventsCount = $user->organizedEvents()->where('status', 'active')->where('date', '>=', now())->count();
        $completedEventsCount = $user->organizedEvents()->where(function($q) { $q->where('status', 'completed')->orWhere('date', '<', now()); })->count();
        
        // Use global counts for these too
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
        $this->storePreviousUrl(); // ADD THIS
        
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