<?php

namespace App\Http\Controllers\EventC;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Services\EventService;
use App\Services\ContentBasedFilteringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Traits\StoresPreviousUrl;

class EventController extends Controller
{   
    use StoresPreviousUrl;
    
    public function __construct(
        private EventService $eventService,
        private ContentBasedFilteringService $cbfService
    ) {}

    public function index()
    {
          $this->storePreviousUrl(); // ADD THIS

        // Store previous URL for back navigation
        $this->storePreviousUrl();
        
        $events = $this->eventService->getAllEventsForPublic();
        
        // Calculate statistics for the dashboard
        $upcomingEvents = Event::where('status', 'active')
                              ->where('date', '>', now())
                              ->count();

        $activeEvents = Event::where('status', 'active')->count();
        
        $eventsNext7Days = Event::where('status', 'active')
                               ->whereBetween('date', [now(), now()->addDays(7)])
                               ->count();

        // User-specific statistics
        $hoursLogged = 0;
        $myCompletedEvents = 0;
        $myUpcomingEvents = 0;
        $myHostedEvents = 0;
        $recommendations = collect();

        if (auth()->check()) {
            $user = auth()->user();
            
            // Calculate hours logged (adjust based on your data structure)
            $hoursLogged = $user->volunteerRegistrations()
                               ->where('status', 'completed')
                               ->count() * 4; // Assuming 4 hours per event

            // Count completed events
            $myCompletedEvents = $user->volunteerRegistrations()
                                     ->where('status', 'completed')
                                     ->count();

            // Count upcoming events
            $myUpcomingEvents = $user->volunteerRegistrations()
                                    ->where('status', 'registered')
                                    ->whereHas('event', function($query) {
                                        $query->where('date', '>', now());
                                    })
                                    ->count();

            // Count hosted events
            $myHostedEvents = Event::where('organizer_id', $user->id)->count();

            // AI Recommendations for the main list (Phase 4)
            $recommendations = collect();
            if (!$user->isVerifiedOrganizer()) {
                $rawRecommendations = $this->cbfService->recommendEventsForVolunteer($user->id, 20);
                
                // Map to events with matching attributes for the view
                $recommendations = $rawRecommendations->map(function($rec) {
                    $event = $rec['event'];
                    $event->match_percentage = $rec['match_percentage'];
                    return $event;
                });

                // ALSO attach these percentages to the main events list so badges show up there too
                $events->each(function($event) use ($rawRecommendations) {
                    $match = $rawRecommendations->firstWhere('event.id', $event->id);
                    if ($match) {
                        $event->match_percentage = $match['match_percentage'];
                    }
                });
            }
        }

        return view('events.index', compact(
            'events',
            'upcomingEvents',
            'activeEvents',
            'eventsNext7Days',
            'hoursLogged',
            'myCompletedEvents',
            'myUpcomingEvents',
            'myHostedEvents',
            'recommendations'
        ));
    }

    public function show(Event $event)
    {   
        $this->storePreviousUrl(); // ADD THIS

        // Store previous URL for back navigation
        $this->storePreviousUrl();
        
        // FIXED: Load volunteers with their user data
        $event->load([
            'organizer',
            'volunteers.volunteer' // This loads the volunteers with their user data
        ]);

        // Fix: Convert storage path to public URL if needed
        if ($event->organizer && $event->organizer->avatar) {
            // If avatar path starts with 'storage/', convert to public URL
            if (str_starts_with($event->organizer->avatar, 'storage/')) {
                $event->organizer->avatar = asset($event->organizer->avatar);
            }
        }

        // AI Recommended Volunteers (Organizer Feature - Phase 4)
        $recommendedVolunteers = collect();
        if (auth()->check() && $event->organizer_id === auth()->id()) {
            $recommendedVolunteers = $this->cbfService->recommendVolunteersForEvent($event->id, 6);
        }

        return view('events.show', compact('event', 'recommendedVolunteers'));
    }

    public function create()

    {  
          $this->storePreviousUrl(); // ADD THIS

        // Store previous URL for back navigation
        $this->storePreviousUrl();
        
        // Only verified organizers can create events
        if (!Auth::user()->isVerifiedOrganizer()) {
            // FIX: Changed from 'organizer.verify' to 'organizer.verification.create'
            return redirect()->route('organizer.verification.create')
                             ->with('error', 'You need to be a verified organizer to create events. Please apply to become an organizer first.');
        }

        return view('events.create');
    }

    public function store(EventRequest $request)
    {
        // Only verified organizers can create events
        if (!Auth::user()->isVerifiedOrganizer()) {
            return redirect()->route('organizer.verification.create')
                             ->with('error', 'You need to be a verified organizer to create events.');
        }

        try {
            $event = $this->eventService->createEvent($request->validated(), Auth::user());

            return redirect()->route('events.show', $event)
                             ->with('success', 'Event created successfully! It is now live and visible to volunteers.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create event: ' . $e->getMessage())
                         ->withInput();
        }
    }

    public function edit(Event $event)
    {
        // Only the event organizer can edit their own event
        if ($event->organizer_id !== Auth::id()) {
            abort(403, 'Unauthorized access. You can only edit your own events.');
        }

        return view('events.edit', compact('event'));
    }

    public function update(EventRequest $request, Event $event)
    {
        // Only the event organizer can update their own event
        if ($event->organizer_id !== Auth::id()) {
            abort(403, 'Unauthorized access. You can only update your own events.');
        }

        try {
            $this->eventService->updateEvent($event, $request->validated());

            return redirect()->route('events.show', $event)
                             ->with('success', 'Event updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update event: ' . $e->getMessage())
                         ->withInput();
        }
    }

    public function destroy(Event $event)
    {
        // Only the event organizer can delete their own event
        if ($event->organizer_id !== Auth::id()) {
            abort(403, 'Unauthorized access. You can only delete your own events.');
        }

        try {
            // Delete associated image if it exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            
            $event->delete();

            return redirect()->route('volunteers.organized-events')
                             ->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete event: ' . $e->getMessage());
        }
    }

    public function register(Event $event)
    {
        try {
            $this->eventService->registerForEvent($event, Auth::user());
            return redirect()->back()->with('success', 'Successfully registered for the event!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function unregister(Event $event)
    {
        try {
            $this->eventService->unregisterFromEvent($event, Auth::user());
            return redirect()->back()->with('success', 'Successfully un registered from the event.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get AI-powered event recommendations for the logged-in volunteer
     */
    public function getRecommendations(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $topN = $request->input('limit', 10);
        $threshold = $request->input('threshold', 0.3);

        $recommendations = $this->cbfService->recommendEventsForVolunteer(
            auth()->id(),
            $topN,
            $threshold
        );

        return response()->json([
            'success' => true,
            'recommendations' => $recommendations,
            'count' => count($recommendations)
        ]);
    }

    /**
     * Get recommended volunteers for an event (Organizer feature)
     */
    public function getRecommendedVolunteers(Event $event, Request $request)
    {
        // Only allow organizers to see recommended volunteers for their own events
        if (!auth()->check() || $event->organizer_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $topN = $request->input('limit', 20);
        $threshold = $request->input('threshold', 0.3);

        $recommendations = $this->cbfService->recommendVolunteersForEvent(
            $event->id,
            $topN,
            $threshold
        );

        return response()->json([
            'success' => true,
            'event' => $event->title,
            'recommendations' => $recommendations,
            'count' => count($recommendations)
        ]);
    }

    /**
     * Store the previous URL in session for back navigation
     * This helps the back button work correctly
     */
    private function storePreviousUrl()
    {
        $previousUrl = url()->previous();
        $currentUrl = url()->current();
        
        // Only store if it's a different page and from our app
        if ($previousUrl !== $currentUrl && str_contains($previousUrl, config('app.url'))) {
            session(['previous_url' => $previousUrl]);
        }
    }
}