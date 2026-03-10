<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FeedbackController extends Controller
{
    /**
     * Show feedback form for a completed event
     */
    public function create(Event $event)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to submit feedback');
        }

        // Check if event is completed
        if (!$event->isCompleted()) {
            return redirect()->route('events.show', $event)->with('error', 'Feedback can only be submitted for completed events');
        }

        // Check if user participated in the event
        if (!$event->isRegistered(auth()->id())) {
            return redirect()->route('events.show', $event)->with('error', 'Only volunteers who participated can submit feedback');
        }

        // Check if feedback already submitted
        $existingFeedback = EventFeedback::where('event_id', $event->id)
            ->where('volunteer_id', auth()->id())
            ->first();

        if ($existingFeedback) {
            return redirect()->route('events.show', $event)->with('info', 'You have already submitted feedback for this event');
        }

        return view('feedback.create', compact('event'));
    }

    /**
     * Store event feedback
     */
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'overall_rating' => 'required|integer|min:1|max:5',
            'organization_rating' => 'nullable|integer|min:1|max:5',
            'impact_rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'would_recommend' => 'required|boolean'
        ]);

        // Check authorization
        if (!$event->isRegistered(auth()->id())) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        // Create feedback
        EventFeedback::create([
            'event_id' => $event->id,
            'volunteer_id' => auth()->id(),
            'overall_rating' => $validated['overall_rating'],
            'organization_rating' => $validated['organization_rating'] ?? null,
            'impact_rating' => $validated['impact_rating'] ?? null,
            'comment' => $validated['comment'] ?? null,
            'would_recommend' => $validated['would_recommend']
        ]);

        return redirect()->route('events.show', $event)->with('success', 'Thank you for your feedback! 🎉');
    }

    /**
     * Display all feedback for an event (Organizer only)
     */
    public function index(Event $event)
    {
        // Check if user is the organizer
        if (!auth()->check() || $event->organizer_id !== auth()->id()) {
            return redirect()->route('events.index')->with('error', 'Unauthorized');
        }

        $feedbacks = EventFeedback::where('event_id', $event->id)
            ->with('volunteer')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = EventFeedback::getAverageRatings($event->id);

        return view('feedback.index', compact('event', 'feedbacks', 'stats'));
    }

    /**
     * Get feedback statistics for an event (API)
     */
    public function stats(Event $event)
    {
        // Check if user is the organizer
        if (!auth()->check() || $event->organizer_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $stats = EventFeedback::getAverageRatings($event->id);

        return response()->json([
            'success' => true,
            'event' => $event->title,
            'stats' => $stats
        ]);
    }
}
