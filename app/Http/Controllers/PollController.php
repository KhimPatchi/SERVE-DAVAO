<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    // List all polls - accessible by all
    public function index()
    {
        $polls = Poll::with(['organizer', 'options', 'votes'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('polls.index', compact('polls'));
    }

    // Show create form - organizers only
    public function create()
    {
        if (!auth()->user()->isVerifiedOrganizer()) {
            return redirect()->route('polls.index')
                ->with('error', 'Only verified organizers can create polls.');
        }
        return view('polls.create');
    }

    // Store new poll + options - organizers only
    public function store(Request $request)
    {
        if (!auth()->user()->isVerifiedOrganizer()) {
            return redirect()->route('polls.index')
                ->with('error', 'Only verified organizers can create polls.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'closes_at'   => 'nullable|date|after:now',
            'options'     => 'required|array|min:2|max:8',
            'options.*'   => 'required|string|max:200',
        ]);

        DB::transaction(function () use ($validated) {
            $poll = Poll::create([
                'user_id'     => auth()->id(),
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? null,
                'closes_at'   => $validated['closes_at'] ?? null,
                'status'      => 'active',
            ]);

            foreach ($validated['options'] as $label) {
                if (trim($label) !== '') {
                    $poll->options()->create(['label' => trim($label)]);
                }
            }
        });

        return redirect()->route('polls.index')
            ->with('success', 'Poll created! Volunteers can now vote.');
    }

    // Show poll + voting form
    public function show(Poll $poll)
    {
        $poll->load(['organizer', 'options', 'votes']);
        $userVote = auth()->check() ? $poll->getUserVote(auth()->id()) : null;
        $totalVotes = $poll->totalVotes();

        return view('polls.show', compact('poll', 'userVote', 'totalVotes'));
    }

    // Cast or change vote
    public function vote(Request $request, Poll $poll)
    {
        if (!$poll->isActive()) {
            return response()->json(['success' => false, 'message' => 'This poll is closed.'], 403);
        }

        $request->validate([
            'option_id' => 'required|exists:poll_options,id',
        ]);

        $option = PollOption::where('id', $request->option_id)
            ->where('poll_id', $poll->id)
            ->firstOrFail();

        $existingVote = PollVote::where('poll_id', $poll->id)
            ->where('user_id', auth()->id())
            ->first();

        DB::transaction(function () use ($poll, $option, $existingVote) {
            if ($existingVote) {
                // Decrement old option
                PollOption::where('id', $existingVote->poll_option_id)->decrement('votes');
                $existingVote->update(['poll_option_id' => $option->id]);
            } else {
                PollVote::create([
                    'poll_id'        => $poll->id,
                    'poll_option_id' => $option->id,
                    'user_id'        => auth()->id(),
                ]);
            }
            $option->increment('votes');
        });

        // Return fresh results
        $poll->load('options');
        $totalVotes = $poll->totalVotes();

        return response()->json([
            'success'     => true,
            'total_votes' => $totalVotes,
            'results'     => $poll->options->map(fn($o) => [
                'id'         => $o->id,
                'label'      => $o->label,
                'votes'      => $o->votes,
                'percentage' => $o->percentage($totalVotes),
            ]),
        ]);
    }

    // Close / reopen poll - organizers only
    public function updateStatus(Request $request, Poll $poll)
    {
        if (!auth()->user()->isVerifiedOrganizer() || $poll->user_id !== auth()->id()) {
            return response()->json(['success' => false], 403);
        }

        $request->validate(['status' => 'required|in:active,closed']);
        $poll->update(['status' => $request->status]);

        return response()->json(['success' => true, 'status' => $poll->status]);
    }
}
