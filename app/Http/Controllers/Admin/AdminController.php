<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\EventVolunteer;
use App\Models\OrganizerVerification;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AdminController extends Controller
{
    public function __construct()
    {
        // Share sidebar data with all admin views
        $this->shareSidebarData();
    }

    private function shareSidebarData()
    {
        // Only share data for admin users
        if (auth()->check() && auth()->user()->isAdmin()) {
            $pendingOrganizerCount = OrganizerVerification::where('status', 'pending')->count();
            $recentOrganizerRequests = OrganizerVerification::with('user')
                ->where('status', 'pending')
                ->latest()
                ->limit(3)
                ->get();

            View::share('pendingOrganizerCount', $pendingOrganizerCount);
            View::share('recentOrganizerRequests', $recentOrganizerRequests);
        }
    }

    public function dashboard()
    {
         // Add no-cache headers
    $response = response()->view('admin.dashboard', [
        'stats' => $this->getDashboardStats()
    ]);
    
    return $response->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                   ->header('Pragma', 'no-cache')
                   ->header('Expires', '0');



        // ✅ UPDATED: Use new OrganizerVerification system
        $totalOrganizers = OrganizerVerification::where('status', 'approved')->count();
        $pendingOrganizers = OrganizerVerification::where('status', 'pending')->count();

        $stats = [
            'total_users' => User::count(),
            'total_organizers' => $totalOrganizers,
            'pending_organizers' => $pendingOrganizers,
            'total_events' => Event::count(),
            'pending_events' => Event::where('status', 'pending')->count(),
            'active_events' => Event::where('status', 'active')->count(),
            'total_volunteers' => EventVolunteer::where('status', 'registered')->count(),
        ];

        // Recent pending events for approval
        $pendingEvents = Event::with('organizer')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        // Recent organizer applications (using new verification system)
        $pendingOrganizerApplications = OrganizerVerification::with('user')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingEvents', 'pendingOrganizerApplications'));
    }

    public function events()
    {
        $events = Event::with('organizer')
            ->latest()
            ->paginate(10);

        return view('admin.events', compact('events'));
    }

    public function approveEvent(Event $event)
    {
        $oldStatus = $event->status;
        $event->markAsActive(auth()->id());

        // Audit logging for event approval
        AuditService::log(
            'event_approved',
            "Admin approved event: {$event->title}",
            get_class($event),
            $event->id,
            [
                'event_title' => $event->title,
                'organizer_id' => $event->organizer_id,
                'organizer_email' => $event->organizer->email ?? 'Unknown',
                'old_status' => $oldStatus,
                'new_status' => 'active'
            ]
        );

        return back()->with('success', 'Event approved successfully!');
    }

    public function rejectEvent(Event $event, Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $oldStatus = $event->status;
        $event->markAsRejected(auth()->id(), $request->reason);

        // Audit logging for event rejection
        AuditService::log(
            'event_rejected',
            "Admin rejected event: {$event->title} - Reason: {$request->reason}",
            get_class($event),
            $event->id,
            [
                'event_title' => $event->title,
                'organizer_id' => $event->organizer_id,
                'organizer_email' => $event->organizer->email ?? 'Unknown',
                'old_status' => $oldStatus,
                'new_status' => 'rejected',
                'reason' => $request->reason
            ]
        );

        return back()->with('success', 'Event rejected successfully!');
    }

    public function users()
    {
        $users = User::withCount([
                'organizedEvents', 
                'volunteeringEvents'
            ])
            ->with(['organizerVerification'])
            ->latest()
            ->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function updateUserRole(User $user, Request $request)
    {
        $request->validate(['role' => 'required|in:user,admin']);

        $oldRole = $user->role;
        $user->update(['role' => $request->role]);

        // Enhanced audit logging
        AuditService::log(
            'user_role_updated',
            "Admin changed user role for {$user->email} from {$oldRole} to {$request->role}",
            get_class($user),
            $user->id,
            [
                'user_email' => $user->email,
                'user_name' => $user->name,
                'old_role' => $oldRole,
                'new_role' => $request->role
            ]
        );

        return back()->with('success', 'User role updated successfully!');
    }

    // ✅ UPDATED: Organizer verification methods using new system
    public function organizerVerifications()
    {
        // Properly eager load the user relationship with organizedEvents
        $applications = OrganizerVerification::with([
            'user' => function($query) {
                $query->select('id', 'name', 'email', 'avatar', 'created_at');
            },
            'user.organizedEvents' => function($query) {
                $query->where('status', 'pending');
            }
        ])
        ->where('status', 'pending')
        ->latest()
        ->paginate(10);

        return view('admin.organizer-verifications', compact('applications'));
    }

    public function approveOrganizer(Request $request, User $user)
    {
        $verification = OrganizerVerification::where('user_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $oldStatus = $verification->status;
        
        $verification->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Enhanced audit logging using AuditService
        AuditService::logOrganizerApproval($user, $verification);

        return back()->with('success', 'Organizer approved successfully!');
    }

    public function rejectOrganizer(User $user, Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $verification = OrganizerVerification::where('user_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $oldStatus = $verification->status;
        
        $verification->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'rejected_at' => now(),
        ]);

        // Enhanced audit logging using AuditService
        AuditService::logOrganizerRejection($user, $verification, $request->reason);

        return back()->with('success', 'Organizer application rejected successfully!');
    }
    public function completeEvent(Event $event)
{
    $oldStatus = $event->status;
    
    // Update event status to completed
    $event->update(['status' => 'completed']);

    // Audit logging for event completion
    AuditService::log(
        'event_completed',
        "Admin marked event as completed: {$event->title}",
        get_class($event),
        $event->id,
        [
            'event_title' => $event->title,
            'organizer_id' => $event->organizer_id,
            'organizer_email' => $event->organizer->email ?? 'Unknown',
            'old_status' => $oldStatus,
            'new_status' => 'completed'
        ]
    );

    return back()->with('success', 'Event marked as completed successfully!');
}
}