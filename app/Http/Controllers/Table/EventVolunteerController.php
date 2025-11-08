<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventVolunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventVolunteerController extends Controller
{
    public function join(Event $event)
    {
        $user = Auth::user();
        
        // Check if already registered
        $existingRegistration = EventVolunteer::where('event_id', $event->id)
            ->where('volunteer_id', $user->id)
            ->first();

        if ($existingRegistration) {
            return back()->with('info', 'You have already joined this event.');
        }

        // Check if event is full
        if ($event->isFull()) {
            return back()->with('error', 'This event is already full.');
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
            return back()->with('success', 'Successfully joined the event!');
        } catch (\Exception $e) {
            Log::error('Join event error: ' . $e->getMessage());
            return back()->with('error', 'Unable to join the event. Please try again.');
        }
    }

    public function leave(Event $event)
    {
        $user = Auth::user();

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
                return back()->with('success', 'Successfully left the event.');
            }

            return back()->with('error', 'You are not registered for this event.');
        } catch (\Exception $e) {
            Log::error('Leave event error: ' . $e->getMessage());
            return back()->with('error', 'Unable to leave the event. Please try again.');
        }
    }
}