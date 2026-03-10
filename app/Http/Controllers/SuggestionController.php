<?php

namespace App\Http\Controllers;

use App\Models\EventSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SuggestionController extends Controller
{
    /**
     * Display all event suggestions
     */
    public function index(Request $request)
    {
        if (!auth()->user()->isVerifiedOrganizer()) {
            return redirect()->route('polls.index')->with('error', 'Only organizers can view the suggestions board. Go to Event Polls to vote on what comes next!');
        }

        $query = EventSuggestion::with('user')->with('suggestionVotes');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Sort
        $sort = $request->input('sort', 'popular');
        if ($sort === 'popular') {
            $query->orderBy('votes', 'desc');
        } elseif ($sort === 'recent') {
            $query->orderBy('created_at', 'desc');
        }

        $suggestions = $query->paginate(12);

        // Get categories for filter
        $categories = EventSuggestion::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('suggestions.index', compact('suggestions', 'categories'));
    }

    /**
     * Show form to create new suggestion
     */
    public function create()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to suggest an event');
        }

        // Volunteers ARE allowed to create (via post-event prompt)
        return view('suggestions.create');
    }

    /**
     * Store a new event suggestion
     */
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'title'                    => 'required|string|max:255',
            'description'              => 'required|string|max:2000',
            'category'                 => 'nullable|string|max:100',
            'location'                 => 'nullable|string|max:255',
            'suggested_after_event_id' => 'nullable|integer',
        ]);

        // Smart Merging: Check if this suggestion title already exists (case-insensitive)
        $trimmedTitle = trim($validated['title']);
        $existingSuggestion = EventSuggestion::whereRaw('LOWER(title) = ?', [strtolower($trimmedTitle)])
            ->active()
            ->first();

        // 1. Record that the user has responded to this specific event prompt (if applicable)
        if ($validated['suggested_after_event_id']) {
            DB::table('event_suggestion_responses')->updateOrInsert(
                ['user_id' => auth()->id(), 'event_id' => $validated['suggested_after_event_id']],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        if ($existingSuggestion) {
            // Match found! If user hasn't voted yet, add their vote to this suggestion
            if (!$existingSuggestion->hasVoted(auth()->id())) {
                $existingSuggestion->toggleVote(auth()->id());
            }
            
            return redirect()->route('polls.index')->with('success', 'Someone already suggested that! We added your vote to the existing idea. 🚀');
        }

        // No match: Create new suggestion
        $suggestion = EventSuggestion::create([
            'user_id'                  => auth()->id(),
            'title'                    => $trimmedTitle,
            'description'              => $validated['description'],
            'category'                 => $validated['category'] ?? null,
            'location'                 => $validated['location'] ?? null,
            'status'                   => 'pending',
            'suggested_after_event_id' => $validated['suggested_after_event_id'],
        ]);

        // Auto-vote for own suggestion
        $suggestion->toggleVote(auth()->id());

        return redirect()->route('polls.index')->with('success', 'Your event suggestion has been submitted! 🎉');
    }

    /**
     * Toggle vote on a suggestion
     */
    public function vote(EventSuggestion $suggestion)
    {
        if (!auth()->check() || !auth()->user()->isVerifiedOrganizer()) {
            return response()->json(['error' => 'Unauthorized. Only organizers can vote on internal suggestions.'], 403);
        }

        $action = $suggestion->toggleVote(auth()->id());

        return response()->json([
            'success' => true,
            'action' => $action,
            'votes' => $suggestion->fresh()->votes
        ]);
    }

    /**
     * Show popular/trending suggestions
     */
    public function popular()
    {
        $suggestions = EventSuggestion::popular(5)
            ->with('user')
            ->take(10)
            ->get();

        return view('suggestions.popular', compact('suggestions'));
    }

    /**
     * Update suggestion status (Organizers only)
     */
    public function updateStatus(Request $request, EventSuggestion $suggestion)
    {
        // Only verified organizers can update status
        if (!auth()->check() || !auth()->user()->isVerifiedOrganizer()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,implemented,rejected',
            'organizer_notes' => 'nullable|string|max:500'
        ]);

        $suggestion->update([
            'status' => $validated['status'],
            'organizer_notes' => $validated['organizer_notes'] ?? null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Suggestion status updated',
            'suggestion' => $suggestion
        ]);
    }
}
