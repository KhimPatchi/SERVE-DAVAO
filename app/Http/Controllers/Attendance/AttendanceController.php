<?php

namespace App\Http\Controllers\Attendance;

use App\Events\VolunteerCompletedEvent;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventVolunteer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Handles QR-based volunteer check-in, organizer scanner view, and event ending.
 *
 * Routes:
 *   GET  /organizer/events/{event}/scan            → scanView()
 *   GET  /organizer/events/{event}/checkin/{volunteer} → checkin()  [signed]
 *   POST /organizer/events/{event}/end             → endEvent()
 */
class AttendanceController extends Controller
{
    // ─── Scanner View ────────────────────────────────────────────────────────

    /**
     * Show the QR scanner page for an event (organizer only).
     */
    public function scanView(Event $event): View|RedirectResponse
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403, 'Only the event organizer can access the scanner.');
        }

        $attended = EventVolunteer::with('volunteer')
            ->where('event_id', $event->id)
            ->attended()
            ->get();

        $registered = EventVolunteer::with('volunteer')
            ->where('event_id', $event->id)
            ->registered()
            ->get();

        return view('attendance.scan', compact('event', 'attended', 'registered'));
    }

    // ─── QR Check-In ─────────────────────────────────────────────────────────

    /**
     * Validate the signed URL and mark the volunteer as attended.
     * This endpoint is hit when the organizer's scanner reads the QR code.
     */
    public function checkin(Request $request, Event $event, User $volunteer): JsonResponse
    {
        // Laravel validates the signed URL signature automatically via the route
        abort_unless(auth()->id() === $event->organizer_id, 403, 'Only the organizer can check in volunteers.');

        $record = EventVolunteer::where('event_id', $event->id)
            ->where('volunteer_id', $volunteer->id)
            ->first();

        if (! $record) {
            return response()->json([
                'success' => false,
                'message' => "{$volunteer->name} is not registered for this event.",
            ], 404);
        }

        if ($record->isAttended()) {
            return response()->json([
                'success' => false,
                'message' => "{$volunteer->name} has already been checked in.",
                'checked_in_at' => $record->check_in_time?->format('h:i A'),
            ], 409);
        }

        if (! $record->isRegistered()) {
            return response()->json([
                'success' => false,
                'message' => "Cannot check in {$volunteer->name}: status is '{$record->status}'.",
            ], 422);
        }

        // Calculate hours volunteered (event duration, capped to 8h default)
        $hoursVolunteered = $this->calculateHours($event);

        $record->update([
            'status'            => 'attended',
            'check_in_time'     => Carbon::now(),
            'hours_volunteered' => $hoursVolunteered,
        ]);

        Log::info("QR Check-in: Volunteer {$volunteer->id} checked into Event {$event->id}", [
            'organizer_id'    => auth()->id(),
            'hours_logged'    => $hoursVolunteered,
            'check_in_time'   => now()->toDateTimeString(),
        ]);

        return response()->json([
            'success'       => true,
            'message'       => "{$volunteer->name} checked in successfully!",
            'volunteer'     => [
                'id'     => $volunteer->id,
                'name'   => $volunteer->name,
                'avatar' => $volunteer->avatar_url,
                'has_avatar' => ($volunteer->avatar || $volunteer->google_avatar),
            ],
            'checked_in_at' => now()->format('h:i A'),
            'hours'         => $hoursVolunteered,
        ]);
    }

    // ─── End Event ───────────────────────────────────────────────────────────

    /**
     * Organizer manually ends the event:
     *  1. Mark remaining 'registered' volunteers as 'no-show'
     *  2. Mark event as 'completed'
     *  3. Fire VolunteerCompletedEvent for each 'attended' volunteer (Reverb)
     */
    public function endEvent(Event $event): JsonResponse
    {
        abort_unless(auth()->id() === $event->organizer_id, 403, 'Only the organizer can end this event.');

        if ($event->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'This event has already been ended.',
            ], 409);
        }

        // 1. Mark no-shows
        $noShowCount = EventVolunteer::where('event_id', $event->id)
            ->registered()
            ->update(['status' => 'no-show']);

        // 2. Complete the event
        $event->update(['status' => 'completed']);

        // 3. Notify attended volunteers via Reverb
        $attendedVolunteers = EventVolunteer::with('volunteer')
            ->where('event_id', $event->id)
            ->attended()
            ->get();

        foreach ($attendedVolunteers as $ev) {
            VolunteerCompletedEvent::dispatch($event, $ev->volunteer);
        }

        // 4. Bust recommendation cache for ALL volunteers of this event
        //    so they immediately get fresh recommendations without needing to save profile.
        EventVolunteer::where('event_id', $event->id)
            ->pluck('volunteer_id')
            ->each(function ($volunteerId) {
                Cache::forget('recommendations_' . $volunteerId);
            });

        Log::info("Event {$event->id} ended by Organizer " . auth()->id(), [
            'no_shows'          => $noShowCount,
            'attended_notified' => $attendedVolunteers->count(),
        ]);

        return response()->json([
            'success'           => true,
            'message'           => 'Event ended. Volunteers have been notified.',
            'no_show_count'     => $noShowCount,
            'attended_count'    => $attendedVolunteers->count(),
        ]);
    }

    // ─── Private Helpers ─────────────────────────────────────────────────────

    /**
     * Calculate hours volunteered based on event duration.
     * Uses explicit end_time if stored, otherwise defaults to 8 hours.
     */
    private function calculateHours(Event $event): float
    {
        $start = $event->date;

        if ($event->end_time) {
            $end = $event->end_time;
        } else {
            $end = $start->copy()->addHours(8);
        }

        return round($start->diffInMinutes($end) / 60, 2);
    }
}
