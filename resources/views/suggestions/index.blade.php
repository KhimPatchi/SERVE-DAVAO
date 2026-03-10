@extends ('layouts.sidebar.sidebar')

@section ('title', 'Event Suggestions | ServeDavao')

@section ('content')
<div class="space-y-8 animate-fade-in">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Event Suggestions 💡</h1>
            <p class="text-gray-500 mt-1 font-medium">Volunteers suggest ideas — organizers post polls to decide!</p>
        </div>
        @auth
            @if (auth()->user()->isVerifiedOrganizer())
                {{-- Manual post button removed as requested --}}
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

    {{-- Filters --}}
    <form method="GET" action="{{ route('suggestions.index') }}"
          class="flex flex-wrap gap-3 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">

        {{-- Sort --}}
        <select name="sort" onchange="this.form.submit()"
                class="px-4 py-2 rounded-xl border border-gray-200 bg-gray-50 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all">
            <option value="popular" {{ request('sort','popular') === 'popular' ? 'selected' : '' }}>🔥 Most Voted</option>
            <option value="recent"  {{ request('sort') === 'recent' ? 'selected' : '' }}>🕓 Most Recent</option>
        </select>

        {{-- Status --}}
        <select name="status" onchange="this.form.submit()"
                class="px-4 py-2 rounded-xl border border-gray-200 bg-gray-50 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all">
            <option value="all"         {{ request('status','all') === 'all' ? 'selected' : '' }}>All Status</option>
            <option value="pending"     {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="reviewed"    {{ request('status') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
            <option value="implemented" {{ request('status') === 'implemented' ? 'selected' : '' }}>Implemented</option>
            <option value="rejected"    {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>

        {{-- Category --}}
        @if ($categories->isNotEmpty())
            <select name="category" onchange="this.form.submit()"
                    class="px-4 py-2 rounded-xl border border-gray-200 bg-gray-50 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        @endif

        {{-- Clear --}}
        @if (request()->anyFilled(['sort','status','category']))
            <a href="{{ route('suggestions.index') }}"
               class="px-4 py-2 rounded-xl border border-gray-200 text-sm font-medium text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-all">
                Clear Filters
            </a>
        @endif
    </form>

    {{-- Suggestions Grid --}}
    @if ($suggestions->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach ($suggestions as $suggestion)
            @php
                $statusColors = [
                    'pending'     => 'bg-amber-50 text-amber-700 border-amber-200',
                    'reviewed'    => 'bg-blue-50 text-blue-700 border-blue-200',
                    'implemented' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'rejected'    => 'bg-red-50 text-red-700 border-red-200',
                ];
                $statusColor = $statusColors[$suggestion->status] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                $hasVoted = auth()->check() && $suggestion->hasVoted(auth()->id());
            @endphp
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-100 transition-all duration-300 p-6 flex flex-col gap-4 group">

                {{-- Top: Status + Category --}}
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusColor }} capitalize">
                        {{ $suggestion->status }}
                    </span>
                    @if ($suggestion->category)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            {{ $suggestion->category }}
                        </span>
                    @endif
                </div>

                {{-- Title & Description --}}
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 text-lg leading-snug group-hover:text-emerald-700 transition-colors">
                        {{ $suggestion->title }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-2 line-clamp-3 leading-relaxed">
                        {{ $suggestion->description }}
                    </p>
                    @if ($suggestion->location)
                        <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                            <i class="bi bi-geo-alt"></i> {{ $suggestion->location }}
                        </p>
                    @endif
                </div>

                {{-- Organizer Notes (if reviewed/implemented) --}}
                @if ($suggestion->organizer_notes)
                    <div class="bg-blue-50 border border-blue-100 rounded-lg px-3 py-2 text-xs text-blue-800">
                        <span class="font-semibold">Organizer note:</span> {{ $suggestion->organizer_notes }}
                    </div>
                @endif

                {{-- Bottom: Submitter + Vote --}}
                <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-[10px]">
                            {{ strtoupper(substr($suggestion->user->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="font-medium">{{ $suggestion->user->name ?? 'Anonymous' }}</span>
                        <span class="text-gray-300">·</span>
                        <span>{{ $suggestion->created_at->diffForHumans() }}</span>
                    </div>

                    {{-- Vote Button --}}
                    @auth
                        <button
                            onclick="toggleVote({{ $suggestion->id }}, this)"
                            data-voted="{{ $hasVoted ? 'true' : 'false' }}"
                            class="vote-btn flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold transition-all
                                   {{ $hasVoted
                                       ? 'bg-emerald-600 text-white hover:bg-emerald-700'
                                       : 'bg-gray-100 text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                            <i class="bi {{ $hasVoted ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }}"></i>
                            <span class="vote-count">{{ $suggestion->votes }}</span>
                        </button>
                    @else
                        <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold bg-gray-100 text-gray-500">
                            <i class="bi bi-hand-thumbs-up"></i>
                            <span>{{ $suggestion->votes }}</span>
                        </span>
                    @endauth
                </div>

                {{-- Organizer Status Update (Verified Organizers Only) --}}
                @auth
                    @if (auth()->user()->isVerifiedOrganizer())
                        <div class="pt-2 border-t border-gray-50">
                            <select onchange="updateStatus({{ $suggestion->id }}, this.value, this)"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs font-medium text-gray-700 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all">
                                <option value="">Organizer: Update Status...</option>
                                @foreach (['pending','reviewed','implemented','rejected'] as $s)
                                    <option value="{{ $s }}" {{ $suggestion->status === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                @endauth
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $suggestions->withQueryString()->links() }}
        </div>

    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
            <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-lightbulb text-3xl text-amber-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900">No event suggestions yet!</h3>
            <p class="text-gray-500 text-sm mt-2 mb-6 max-w-sm mx-auto">
                Volunteers suggest event ideas here. Verified organizers can then turn popular ideas into official polls!
            </p>
            @auth
                @if (auth()->user()->isVerifiedOrganizer())
                    {{-- Empty state button removed --}}
                @endif
            @endauth
        </div>
    @endif
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

async function toggleVote(suggestionId, btn) {
    const wasVoted = btn.dataset.voted === 'true';
    const countEl = btn.querySelector('.vote-count');

    // Optimistic UI update
    btn.dataset.voted = wasVoted ? 'false' : 'true';
    countEl.textContent = parseInt(countEl.textContent) + (wasVoted ? -1 : 1);
    btn.className = btn.className.replace(
        wasVoted
            ? 'bg-emerald-600 text-white hover:bg-emerald-700'
            : 'bg-gray-100 text-gray-600 hover:bg-emerald-50 hover:text-emerald-700',
        wasVoted
            ? 'bg-gray-100 text-gray-600 hover:bg-emerald-50 hover:text-emerald-700'
            : 'bg-emerald-600 text-white hover:bg-emerald-700'
    );
    const icon = btn.querySelector('i');
    icon.className = wasVoted ? 'bi bi-hand-thumbs-up' : 'bi bi-hand-thumbs-up-fill';

    try {
        const res = await fetch(`/suggestions/${suggestionId}/vote`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.success) {
            countEl.textContent = data.votes;
        }
    } catch (e) {
        // Rollback on error
        btn.dataset.voted = wasVoted ? 'true' : 'false';
        countEl.textContent = parseInt(countEl.textContent) + (wasVoted ? 1 : -1);
    }
}

async function updateStatus(suggestionId, status, selectEl) {
    if (!status) return;
    const note = prompt('Optional: Add a note for volunteers (leave empty to skip)') ?? '';
    try {
        const res = await fetch(`/suggestions/${suggestionId}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status, organizer_notes: note })
        });
        if (res.ok) location.reload();
    } catch(e) {
        alert('Failed to update status. Please try again.');
    }
}
</script>
@endsection

