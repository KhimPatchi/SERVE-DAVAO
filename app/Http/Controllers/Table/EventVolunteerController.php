<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventVolunteer;
use App\Services\ConversationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventVolunteerController extends Controller
{
    protected $conversationService;

    public function __construct(ConversationService $conversationService)
    {
        $this->conversationService = $conversationService;
    }

    // ─── QR Ticket ──────────────────────────────────────────────────────────

    /**
     * Generate a signed QR ticket for the authenticated volunteer.
     * Returns the QR code as an inline SVG string inside a JSON response.
     *
     * GET /events/{event}/ticket
     */
    public function ticket(Event $event): JsonResponse
    {
        $user = Auth::user();

        // Only registered volunteers can view their ticket
        $registration = EventVolunteer::where('event_id', $event->id)
            ->where('volunteer_id', $user->id)
            ->whereIn('status', ['registered', 'attended'])
            ->first();

        abort_unless($registration, 403, 'You do not have a valid registration for this event.');

        // Build a signed URL (valid for 24 hours) pointing to the check-in endpoint
        $signedUrl = URL::signedRoute('attendance.checkin', [
            'event'     => $event->id,
            'volunteer' => $user->id,
        ], now()->addHours(24));

        // Generate an SVG QR code — no ext-gd required for SVG format
        $qrSvg = QrCode::size(260)
            ->style('round')
            ->eye('circle')
            ->generate($signedUrl);

        return response()->json([
            'success'    => true,
            'qr_svg'     => (string) $qrSvg,
            'event_name' => $event->title,
            'event_date' => $event->date->format('M d, Y · g:i A'),
            'status'     => $registration->status,
            'expires_at' => now()->addHours(24)->format('M d, Y g:i A'),
        ]);
    }

    // ─── Join / Leave ───────────────────────────────────────────────────────

    public function join(Event $event)
    {
        $user = Auth::user();
        
        // Check if already registered
        if ($event->isRegistered($user->id)) {
            return back()->with('info', 'You have already joined this event.');
        }

        // Use model method for consistent validation
        if (!$event->canBeJoined($user->id)) {
            $reason = $this->getJoinRejectionReason($event, $user->id);
            Log::info("BLOCKING JOIN - {$reason}");
            return back()->with('error', $reason);
        }

        try {
            // Create the volunteer registration
            EventVolunteer::create([
                'event_id' => $event->id,
                'volunteer_id' => $user->id,
                'status' => 'registered',
                'hours_volunteered' => 0
            ]);

            // Update event volunteer count
            $event->increment('current_volunteers');

            Log::info("User {$user->id} joined event {$event->id}");

            // Clear recommendations cache so the list updates (excludes joined event)
            Cache::forget('recommendations_' . $user->id);

            return back()->with('success', 'Successfully joined the event!');
        } catch (\Exception $e) {
            Log::error('Join event error: ' . $e->getMessage());
            return back()->with('error', 'Unable to join the event. Please try again.');
        }
    }

    public function leave(Event $event)
    {
        $user = Auth::user();

        // Use model method for consistent validation
        if (!$event->canBeLeft($user->id)) {
            $reason = $this->getLeaveRejectionReason($event, $user->id);
            Log::info("BLOCKING LEAVE - {$reason}");
            return back()->with('error', $reason);
        }

        try {
            // Find and delete the registration
            $registration = EventVolunteer::where('event_id', $event->id)
                ->where('volunteer_id', $user->id)
                ->first();

            if ($registration) {
                $registration->delete();
                
                // Update event volunteer count
                $event->decrement('current_volunteers');
                
                Log::info("User {$user->id} left event {$event->id}");

                // Clear recommendations cache so the list updates
                Cache::forget('recommendations_' . $user->id);

                return back()->with('success', 'Successfully left the event.');
            }

            return back()->with('error', 'You are not registered for this event.');
        } catch (\Exception $e) {
            Log::error('Leave event error: ' . $e->getMessage());
            return back()->with('error', 'Unable to leave the event. Please try again.');
        }
    }

    /**
     * Get detailed reason why user cannot join
     */
    private function getJoinRejectionReason(Event $event, $userId)
    {
        if (!$event->isActive()) {
            return 'This event is not currently accepting volunteers.';
        }

        if ($event->hasStarted()) {
            return $event->hasEnded() 
                ? 'This event has already ended. Registration is closed.'
                : 'This event has already started. Registration is closed.';
        }

        if ($event->isFull()) {
            return 'This event is already full.';
        }

        if ($event->isRegistered($userId)) {
            return 'You have already joined this event.';
        }

        if ($event->isOrganizer($userId)) {
            return 'You cannot join an event you are organizing.';
        }

        return 'This event cannot be joined at this time.';
    }

    /**
     * Get detailed reason why user cannot leave
     */
    private function getLeaveRejectionReason(Event $event, $userId)
    {
        if (!$event->isRegistered($userId)) {
            return 'You are not registered for this event.';
        }

        if ($event->hasStarted()) {
            return $event->hasEnded() 
                ? 'Cannot leave a completed event.'
                : 'Cannot leave an ongoing event.';
        }

        return 'Cannot leave this event at this time.';
    }
}