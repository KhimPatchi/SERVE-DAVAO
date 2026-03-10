<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Services\EventService;
use App\Services\ContentBasedFilteringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\EventVolunteer; // ← ADD THIS IMPORT
use App\Traits\StoresPreviousUrl; // ADD THIS

class VolunteerController extends Controller
{
    use StoresPreviousUrl; // ADD THIS

    public function __construct(
        private EventService $eventService,
        private ContentBasedFilteringService $cbfService
    ) {}

    public function index()
    {
        $this->storePreviousUrl(); // ADD THIS

        // Get CBF recommendations if user is authenticated
        $recommendedEvents = collect();
        $otherEvents = collect();
        
        if (Auth::check()) {
            $userId = Auth::id();
            $recommendations = $this->cbfService->recommendEventsForVolunteer($userId, 20);
            
            // Get recommended event IDs
            $recommendedEventIds = $recommendations->pluck('event.id')->toArray();
            
            // Attach match_percentage to the event objects
            $recommendedEvents = $recommendations->map(function($rec) {
                $event = $rec['event'];
                $event->match_percentage = $rec['match_percentage'];
                return $event;
            });
            
            // Get other events (not in recommendations) - USE COLLECTION FOR SPLITTING
            $allEventsCollection = $this->eventService->getAllAvailableEventsCollection();
            $otherEvents = $allEventsCollection->whereNotIn('id', $recommendedEventIds);
        } else {
            // Not authenticated - show all events normally
            $otherEvents = $this->eventService->getAllAvailableEventsCollection();
        }
        
        return response()
            ->view('volunteers.index', [
                'recommendedEvents' => $recommendedEvents,
                'otherEvents' => $otherEvents,
                'events' => $this->eventService->getAvailableEvents() // This is now back to being a Paginator
            ])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function myEvents(Request $request)
    {
        $this->storePreviousUrl();

        $user = Auth::user();
        $search = $request->input('search');
        
        // 1. Upcoming & Active Events
        $eventsQuery = $user->allVolunteeringEvents()
            ->with('organizer')
            ->where(function($query) {
                $query->where('events.date', '>', now()->subDay())
                      ->orWhere(function($q) {
                          $q->whereDate('events.date', now()->toDateString());
                      });
            })
            ->where('events.status', 'active')
            ->whereNotIn('event_volunteers.status', ['cancelled']);

        if ($search) {
            $eventsQuery->where(function($q) use ($search) {
                $q->where('events.title', 'like', "%{$search}%")
                  ->orWhere('events.description', 'like', "%{$search}%")
                  ->orWhere('events.location', 'like', "%{$search}%");
            });
        }

        $events = $eventsQuery->orderBy('events.date', 'asc')
            ->paginate(10)
            ->withQueryString();

        // 2. Attended/History Events
        // Filter: Event is done (past/completed) OR user is done (attended/completed)
        $attendedEvents = $user->allVolunteeringEvents()
            ->with(['organizer'])
            ->where(function($query) {
                $query->where('events.status', 'completed')
                      ->orWhere('events.date', '<', now());
            })
            ->orderBy('events.date', 'desc')
            ->get();

        // 3. Statistics (Based on full history)
        $allParticipations = $user->allVolunteeringEvents()->get();
        $now = now();

        $upcomingCount = $allParticipations->filter(function($e) use ($now) {
            return $e->status === 'active' && $e->date > $now && $e->pivot->status === 'registered';
        })->count();

        $ongoingCount = $allParticipations->filter(function($e) use ($now) {
            // Very simple ongoing check: same day
            return $e->status === 'active' && $e->date->isToday();
        })->count();

        $completedCount = $attendedEvents->count();

        // Only count hours for completed events where the user actually attended
        $totalHours = $user->volunteerRegistrations()
            ->where('status', 'attended')
            ->whereHas('event', fn($q) => $q->where('status', 'completed'))
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
        
        // Only the event organizer can view their event's volunteers
        if ($event->organizer_id !== Auth::id()) {
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