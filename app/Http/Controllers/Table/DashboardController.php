<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Services\EventService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private EventService $eventService
    ) {}

    public function index()
    {
        $user = Auth::user();
        
        // Get statistics
        $stats = $this->eventService->getUserVolunteerStats($user);
        
        // Get appropriate events based on user role
        if ($user->isVerifiedOrganizer()) {
            $events = $user->organizedEvents()
                        ->withCount('volunteers')
                        ->where('date', '>=', now())
                        ->orderBy('date', 'asc')
                        ->limit(3)
                        ->get();
        } else {
            $events = $user->volunteeringEvents()
                        ->with('organizer')
                        ->where('date', '>=', now())
                        ->orderBy('date', 'asc')
                        ->limit(3)
                        ->get();
        }

        // Pass all data to the view WITH CACHE CONTROL
        return response()
            ->view('contentside.dashboard', [
                'totalVolunteers' => $stats['total_volunteers'] ?? 0,
                'upcomingEvents' => $stats['upcoming_events'] ?? 0,
                'totalHours' => $stats['total_hours'] ?? 0,
                'events' => $events,
                'user' => $user
            ])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}