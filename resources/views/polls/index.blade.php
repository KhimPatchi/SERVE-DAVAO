@extends ('layouts.sidebar.sidebar')

@section ('title', 'Event Polls | ServeDavao')

@section ('content')
<div class="space-y-8 animate-fade-in">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Event Polls ðŸ—³ï¸</h1>
            <p class="text-gray-500 mt-1 font-medium">Organizers post polls â€” volunteers vote to shape what's next!</p>
        </div>
        @auth
            @if (auth()->user()->isVerifiedOrganizer())
                <a href="{{ route('polls.create') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-emerald-700 active:scale-95 transition-all shadow-lg hover:shadow-emerald-200 hover:-translate-y-0.5 text-sm">
                    <i class="bi bi-plus-lg"></i> Create a Poll
                </a>
            @endif
        @endauth
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-4 flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-emerald-500 text-lg"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-4 flex items-center gap-3">
            <i class="bi bi-exclamation-circle-fill text-red-400 text-lg"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Poll Cards --}}
    @if ($polls->isNotEmpty())
        <div class="space-y-5">
            @foreach ($polls as $poll)
            @php
                $totalVotes = $poll->totalVotes();
                $isActive   = $poll->isActive();
                $userVote   = auth()->check() ? $poll->getUserVote(auth()->id()) : null;
                $hasVoted   = $userVote !== null;
            @endphp

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-100 transition-all duration-300 overflow-hidden group">
                {{-- Status bar --}}
                <div class="h-1 w-full {{ $isActive ? 'bg-gradient-to-r from-emerald-500 to-fuchsia-500' : 'bg-gray-200' }}"></div>

                <div class="p-6 space-y-4">
                    {{-- Header row --}}
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border
                                    {{ $isActive ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-50 text-gray-500 border-gray-200' }}">
                                    {{ $isActive ? 'ðŸŸ¢ Active' : 'ðŸ”´ Closed' }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    by {{ $poll->organizer->name ?? 'Organizer' }} Â· {{ $poll->created_at->diffForHumans() }}
                                </span>
                                @if ($poll->closes_at)
                                    <span class="text-xs text-amber-600 font-medium">
                                        <i class="bi bi-clock"></i>
                                        {{ $poll->closes_at->isPast() ? 'Closed' : 'Closes ' . $poll->closes_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-emerald-700 transition-colors leading-snug">
                                {{ $poll->title }}
                            </h3>
                            @if ($poll->description)
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($poll->description, 120) }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-gray-600 bg-gray-100 px-3 py-1.5 rounded-lg whitespace-nowrap">
                                <i class="bi bi-people-fill mr-1"></i>{{ $totalVotes }} vote{{ $totalVotes !== 1 ? 's' : '' }}
                            </span>
                            <a href="{{ route('polls.show', $poll) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl text-sm font-semibold transition-all
                                   {{ $hasVoted || !$isActive
                                       ? 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                       : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow hover:shadow-emerald-200 hover:-translate-y-0.5' }}">
                                {{ $hasVoted ? 'View Results' : ($isActive ? 'Vote Now' : 'See Results') }}
                                <i class="bi {{ $hasVoted ? 'bi-bar-chart-fill' : 'bi-arrow-right' }}"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Preview of options (first 3) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2">
                        @foreach ($poll->options->take(3) as $option)
                        @php $pct = $option->percentage($totalVotes); @endphp
                        <div class="relative bg-gray-50 rounded-xl px-4 py-3 overflow-hidden border border-gray-100">
                            <div class="absolute inset-0 bg-emerald-100 rounded-xl transition-all duration-500"
                                 style="width: {{ $pct }}%; opacity: 0.5;"></div>
                            <div class="relative flex items-center justify-between gap-2">
                                <span class="text-sm font-medium text-gray-800 truncate">
                                    @if ($hasVoted && $userVote->poll_option_id == $option->id)
                                        <i class="bi bi-check-circle-fill text-emerald-600 mr-1"></i>
                                    @endif
                                    {{ $option->label }}
                                </span>
                                <span class="text-xs font-bold text-emerald-700 whitespace-nowrap">{{ $pct }}%</span>
                            </div>
                        </div>
                        @endforeach
                        @if ($poll->options->count() > 3)
                            <div class="flex items-center justify-center bg-gray-50 rounded-xl px-4 py-3 border border-gray-100 border-dashed text-sm text-gray-400 font-medium">
                                +{{ $poll->options->count() - 3 }} more options
                            </div>
                        @endif
                    </div>

                    {{-- Organizer controls --}}
                    @auth
                        @if (auth()->user()->isVerifiedOrganizer() && $poll->user_id === auth()->id())
                            <div class="flex justify-end pt-1">
                                <button onclick="togglePollStatus({{ $poll->id }}, '{{ $isActive ? 'closed' : 'active' }}', this)"
                                        class="text-xs font-medium px-3 py-1.5 rounded-lg border transition-all
                                               {{ $isActive
                                                   ? 'border-red-200 text-red-600 hover:bg-red-50'
                                                   : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}">
                                    {{ $isActive ? 'ðŸ”´ Close Poll' : 'ðŸŸ¢ Reopen Poll' }}
                                </button>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $polls->links() }}</div>

    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
            <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-bar-chart text-3xl text-emerald-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900">No polls yet!</h3>
            <p class="text-gray-500 text-sm mt-2 mb-6 max-w-sm mx-auto">Organizers create polls to let volunteers vote on what events to run next.</p>
            @auth
                @if (auth()->user()->isVerifiedOrganizer())
                    <a href="{{ route('polls.create') }}"
                       class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-emerald-700 transition-all shadow-lg hover:-translate-y-0.5 text-sm">
                        <i class="bi bi-plus-lg"></i> Create the First Poll
                    </a>
                @endif
            @endauth
        </div>
    @endif
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

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

