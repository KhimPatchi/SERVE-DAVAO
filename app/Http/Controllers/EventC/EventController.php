<?php

namespace App\Http\Controllers\EventC;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function __construct(
        private EventService $eventService
    ) {}

    public function index()
    {
        $events = $this->eventService->getAllEventsForPublic();
        return view('events.index', compact('events'));
    }

  public function show(Event $event)
{
    // Load the organizer relationship properly
    $event->load(['organizer']);

    // Fix: Convert storage path to public URL if needed
    if ($event->organizer && $event->organizer->avatar) {
        // If avatar path starts with 'storage/', convert to public URL
        if (str_starts_with($event->organizer->avatar, 'storage/')) {
            $event->organizer->avatar = asset($event->organizer->avatar);
        }
    }

    return view('events.show', compact('event'));
}
    public function create()
    {
        // Only verified organizers and admins can create events
        if (!Auth::user()->isVerifiedOrganizer() && !Auth::user()->isAdmin()) {
            // FIX: Changed from 'organizer.verify' to 'organizer.verification.create'
            return redirect()->route('organizer.verification.create')
                             ->with('error', 'You need to be a verified organizer to create events. Please apply to become an organizer first.');
        }

        return view('events.create');
    }

    public function store(EventRequest $request)
    {
        // Only verified organizers and admins can create events
        if (!Auth::user()->isVerifiedOrganizer() && !Auth::user()->isAdmin()) {
            // FIX: Changed from 'organizer.verify' to 'organizer.verification.create'
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
            return redirect()->back()->with('success', 'Successfully unregistered from the event.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}