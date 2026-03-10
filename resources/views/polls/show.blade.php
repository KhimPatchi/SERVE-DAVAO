@extends ('layouts.sidebar.sidebar')

@section ('title', $poll->title . ' | ServeDavao')

@section ('content')
@php
    $isActive  = $poll->isActive();
    $hasVoted  = $userVote !== null;
    $leadOption = $poll->options->sortByDesc('votes')->first();
@endphp

<div class="max-w-2xl mx-auto space-y-8 animate-fade-in">

    {{-- Back --}}
    <a href="{{ route('polls.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800 font-medium transition-colors">
        <i class="bi bi-arrow-left"></i> Back to Polls
    </a>

    {{-- Poll Header --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="h-1.5 w-full {{ $isActive ? 'bg-gradient-to-r from-emerald-500 to-fuchsia-500' : 'bg-gray-300' }}"></div>
        <div class="p-7 space-y-2">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border
                    {{ $isActive ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-50 text-gray-500 border-gray-200' }}">
                    {{ $isActive ? 'ðŸŸ¢ Active' : 'ðŸ”´ Closed' }}
                </span>
                @if ($poll->closes_at)
                    <span class="text-xs text-amber-600 font-medium">
                        <i class="bi bi-clock"></i>
                        {{ $poll->closes_at->isPast() ? 'Ended' : 'Closes ' . $poll->closes_at->diffForHumans() }}
                    </span>
                @endif
                <span class="text-xs text-gray-400">by {{ $poll->organizer->name ?? 'Organizer' }} Â· {{ $poll->created_at->diffForHumans() }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 leading-snug">{{ $poll->title }}</h1>
            @if ($poll->description)
                <p class="text-gray-500 text-sm leading-relaxed">{{ $poll->description }}</p>
            @endif
            <p class="text-sm font-semibold text-gray-500 pt-1">
                <i class="bi bi-people-fill mr-1"></i>
                <span id="total-votes-label">{{ $totalVotes }} vote{{ $totalVotes !== 1 ? 's' : '' }}</span>
            </p>
        </div>
    </div>

    {{-- Voting Area --}}
    @auth
        @if ($isActive && !$hasVoted)
            {{-- Cast vote --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-7 space-y-4">
                <h2 class="font-bold text-gray-900 text-lg">Cast Your Vote</h2>
                <p class="text-sm text-gray-500">Select one option. You can change your vote anytime while the poll is active.</p>
                <div id="vote-options" class="space-y-3">
                    @foreach ($poll->options as $option)
                    <button type="button"
                            onclick="castVote({{ $poll->id }}, {{ $option->id }}, this)"
                            class="vote-option w-full text-left flex items-center gap-4 px-5 py-4 rounded-xl border-2 border-gray-100 bg-gray-50
                                   hover:border-emerald-400 hover:bg-emerald-50 hover:shadow-sm transition-all duration-200 group">
                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-hover:border-emerald-500 flex-shrink-0 transition-colors"></div>
                        <span class="font-medium text-gray-800 group-hover:text-emerald-800 text-sm transition-colors">{{ $option->label }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

        @else
            {{-- Results (voted or closed) --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-7 space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-gray-900 text-lg">Results</h2>
                    @if ($hasVoted && $isActive)
                        <button onclick="showChangeVote()" id="change-vote-btn"
                                class="text-xs text-emerald-600 font-semibold hover:text-emerald-800 transition-colors underline underline-offset-2">
                            Change my vote
                        </button>
                    @endif
                </div>

                {{-- Change-vote panel (hidden by default) --}}
                @if ($hasVoted && $isActive)
                <div id="change-vote-panel" class="hidden space-y-3 bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                    <p class="text-sm font-semibold text-emerald-800">Choose a different option:</p>
                    @foreach ($poll->options as $option)
                    <button type="button"
                            onclick="castVote({{ $poll->id }}, {{ $option->id }}, this)"
                            class="vote-option w-full text-left flex items-center gap-4 px-4 py-3 rounded-xl border-2 transition-all duration-200
                                   {{ $userVote->poll_option_id == $option->id
                                       ? 'border-emerald-500 bg-white'
                                       : 'border-gray-100 bg-white hover:border-emerald-300 hover:bg-emerald-50' }}">
                        @if ($userVote->poll_option_id == $option->id)
                            <i class="bi bi-check-circle-fill text-emerald-600 flex-shrink-0"></i>
                        @else
                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex-shrink-0"></div>
                        @endif
                        <span class="font-medium text-gray-800 text-sm">{{ $option->label }}</span>
                    </button>
                    @endforeach
                </div>
                @endif

                {{-- Results bars --}}
                <div id="results-container" class="space-y-3">
                    @foreach ($poll->options->sortByDesc('votes') as $option)
                    @php
                        $pct = $option->percentage($totalVotes);
                        $isMyVote = $hasVoted && $userVote->poll_option_id == $option->id;
                        $isLeader = $leadOption && $option->id === $leadOption->id && $totalVotes > 0;
                    @endphp
                    <div class="option-result" data-option-id="{{ $option->id }}">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                @if ($isMyVote)
                                    <i class="bi bi-check-circle-fill text-emerald-600 text-sm"></i>
                                @endif
                                @if ($isLeader)
                                    <i class="bi bi-trophy-fill text-amber-400 text-sm"></i>
                                @endif
                                <span class="text-sm font-semibold text-gray-800">{{ $option->label }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="option-votes text-xs text-gray-500 font-medium">{{ $option->votes }} vote{{ $option->votes !== 1 ? 's' : '' }}</span>
                                <span class="option-pct text-sm font-bold {{ $isLeader ? 'text-emerald-700' : 'text-gray-500' }}">{{ $pct }}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                            <div class="option-bar h-3 rounded-full transition-all duration-700
                                        {{ $isLeader ? 'bg-gradient-to-r from-emerald-500 to-fuchsia-500' : 'bg-gray-300' }}"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Organizer controls --}}
        @if (auth()->user()->isVerifiedOrganizer() && $poll->user_id === auth()->id())
        <div class="flex justify-end">
            <button onclick="togglePollStatus({{ $poll->id }}, '{{ $isActive ? 'closed' : 'active' }}', this)"
                    class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-xl border transition-all
                           {{ $isActive ? 'border-red-200 text-red-600 hover:bg-red-50' : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}">
                {{ $isActive ? 'ðŸ”´ Close This Poll' : 'ðŸŸ¢ Reopen This Poll' }}
            </button>
        </div>
        @endif

    @else
        <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 text-amber-800 text-sm font-medium flex items-center gap-3">
            <i class="bi bi-info-circle-fill text-amber-500"></i>
            <a href="{{ route('login') }}" class="underline">Log in</a> to vote on this poll.
        </div>
    @endauth
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

async function castVote(pollId, optionId, btn) {
    // Visual feedback
    document.querySelectorAll('.vote-option').forEach(b => b.classList.remove('border-emerald-500', 'bg-emerald-50'));
    btn.classList.add('border-emerald-500', 'bg-emerald-50');

    try {
        const res = await fetch(`/polls/${pollId}/vote`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ option_id: optionId })
        });
        const data = await res.json();
        if (data.success) {
            // Reload to show updated results cleanly
            location.reload();
        }
    } catch(e) {
        alert('Failed to submit vote. Please try again.');
    }
}

function showChangeVote() {
    const panel = document.getElementById('change-vote-panel');
    panel.classList.toggle('hidden');
}

async function togglePollStatus(pollId, newStatus, btn) {
    try {
        const res = await fetch(`/polls/${pollId}/status`, {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ status: newStatus })
        });
        if (res.ok) location.reload();
    } catch(e) { alert('Failed to update poll status.'); }
}
</script>
@endsection

