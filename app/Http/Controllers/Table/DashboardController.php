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
    // ✅ FIXED: Get the user FIRST before checking their role
    $user = Auth::user();
    
    // ✅ FIXED: Now check if user is admin and redirect
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    
    $this->storePreviousUrl();

    // Add user-specific cache busting
    $userHash = md5($user->id . $user->role . now()->timestamp);
    
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

    // USER-SPECIFIC CACHE BUSTING
    return response()
        ->view('contentside.dashboard', [
            'totalVolunteers' => $stats['total_volunteers'] ?? 0,
            'upcomingEvents' => $stats['upcoming_events'] ?? 0,
            'totalHours' => $stats['total_hours'] ?? 0,
            'events' => $events,
            'user' => $user,
            'cacheBuster' => $userHash // Add this
        ])
        ->withHeaders([
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate, private',
            'Pragma' => 'no-cache',
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
            'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
        ]);
}       

    /**
     * Store the previous URL in session for back navigation
     * This fixes the "Method does not exist" error
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