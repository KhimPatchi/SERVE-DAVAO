<?php
// app/Http\Controllers\Audit\AuditController.php

namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Audit;
use App\Models\User;
use App\Models\OrganizerVerification;
use Illuminate\Support\Facades\View;

class AuditController extends Controller
{
    public function __construct()
    {
        // Share sidebar data with audit views
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

    public function logs(Request $request)
    {
        $query = Audit::with(['user', 'auditable'])
                    ->where(function($q) {
                        $q->where('auditable_type', 'App\Models\User')
                          ->orWhere('auditable_type', 'App\Models\OrganizerVerification');
                    })
                    ->latest();

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('action', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQ) use ($request) {
                      $userQ->where('email', 'like', '%' . $request->search . '%')
                            ->orWhere('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Action type filter
        if ($request->has('action') && $request->action && $request->action != 'all') {
            $query->where('action', $request->action);
        }

        // Date filter
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(15);

        // Statistics
        $totalLogs = Audit::count();
        $todayLogs = Audit::whereDate('created_at', today())->count();
        $organizerApprovals = Audit::where('action', 'organizer_approved')->count();
        $organizerRejections = Audit::where('action', 'organizer_rejected')->count();

        return view('admin.audit.logs', compact(
            'logs', 
            'totalLogs', 
            'todayLogs', 
            'organizerApprovals', 
            'organizerRejections'
        ));
    }

    // ... keep your other methods
}