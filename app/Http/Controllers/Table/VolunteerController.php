<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

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

        return response()
            ->view('volunteers.my-events', compact('events'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

 public function organizedEvents()
{
    // FIXED: Check if user is organizer instead of using authorize
    if (!Auth::user()->isVerifiedOrganizer() && !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized access. You need to be a verified organizer.');
    }
    
    // FIXED: Only show upcoming events (events with date in future)
    $events = Auth::user()->organizedEvents()
                ->where('date', '>=', now()) // This filters out past events
                ->withCount('volunteers')
                ->orderBy('date', 'asc') // Show upcoming events first
                ->paginate(10);

    return response()
        ->view('volunteers.organized-events', compact('events'))
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
}
    public function eventVolunteers(Event $event)
    {
        // FIXED: Check if user owns the event or is admin
        if ($event->organizer_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access. You can only manage your own events.');
        }
        
        $volunteers = $event->volunteers()->with('volunteer')->get();
        return response()
            ->view('volunteers.event-volunteers', compact('event', 'volunteers'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}