@extends ('layouts.sidebar.sidebar')

@section ('title', 'Volunteer Opportunities - ServeDavao')

@section ('content')
<div class="min-h-screen bg-gray-50/60 p-6">
    <!-- Professional Page Header -->
    <header class="mb-10">
        <div class="flex flex-col gap-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="relative">
                        <div class="absolute inset-0 bg-emerald-500 blur-xl opacity-20 animate-pulse"></div>
                        <div class="relative rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-4 shadow-lg shadow-emerald-200/50">
                            <i class="bi bi-people text-3xl text-white"></i>
                        </div>
                    </div>
                    <div>
                        <nav class="mb-1 flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-emerald-600/60">
                            <a href="{{ route('dashboard') }}" class="transition-colors hover:text-emerald-700">Dashboard</a>
                            <i class="bi bi-chevron-right text-[10px]"></i>
                            <span class="text-gray-400">Opportunities</span>
                        </nav>
                        <h1 class="text-4xl font-black text-gray-900 tracking-tight">Volunteer <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Opportunities</span></h1>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('volunteers.my-events') }}" 
                       class="group relative inline-flex items-center gap-3 overflow-hidden rounded-2xl bg-white px-8 py-4 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-gray-200 transition-all hover:bg-gray-50 hover:shadow-md active:scale-95">
                        <div class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b from-blue-500 to-purple-600 transition-all group-hover:w-full group-hover:opacity-[0.03]"></div>
                        <i class="bi bi-calendar-check text-lg text-blue-600 transition-transform group-hover:scale-110"></i>
                        <span class="relative">My Registered Events</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Refined Search & Filter Section -->
    <section class="mb-10">
        <div class="relative rounded-2xl bg-white p-2 shadow-sm ring-1 ring-gray-200">
            <div class="flex flex-col lg:flex-row items-stretch gap-2">
                <!-- Search Input Group -->
                <div class="relative flex-1 group/search">
                    <div class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within/search:text-emerald-600">
                       
                    </div>
                    <input type="text" 
                           placeholder="Search by title, location, or required skills..." 
                           class="w-full rounded-xl border-0 bg-transparent py-4 pl-14 pr-6 text-sm font-medium text-gray-900 placeholder-gray-400 focus:ring-0"
                           id="opportunitySearch">
                </div>
                
                <div class="hidden lg:block w-px bg-gray-100 my-4"></div>

                <!-- Filters Group -->
                <div class="flex flex-col sm:flex-row items-center gap-2 p-1">
                    <div class="relative w-full sm:w-auto min-w-[160px]">
                        <select class="w-full appearance-none rounded-xl border-0 bg-gray-50 py-3 pl-4 pr-10 text-xs font-bold text-gray-600 transition-all hover:bg-gray-100 focus:bg-white focus:ring-1 focus:ring-emerald-500 cursor-pointer">
                            <option>All Opportunities</option>
                            <option>Urgent Only</option>
                            <option>Nearby</option>
                        </select>
                        <i class="bi bi-chevron-expand absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                    </div>
                    
                    <div class="relative w-full sm:w-auto min-w-[160px]">
                        <select class="w-full appearance-none rounded-xl border-0 bg-gray-50 py-3 pl-4 pr-10 text-xs font-bold text-gray-600 transition-all hover:bg-gray-100 focus:bg-white focus:ring-1 focus:ring-emerald-500 cursor-pointer">
                            <option>Sort: Newest</option>
                            <option>Sort: Most Needed</option>
                        </select>
                        <i class="bi bi-chevron-expand absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                    </div>

                    <button class="w-full sm:w-auto rounded-xl bg-emerald-600 px-6 py-3 text-sm font-bold text-white transition-all hover:bg-emerald-700 active:scale-95 shadow-sm">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Recommended Events Section -->
    @if (Auth::check() && $recommendedEvents->isNotEmpty())
    <section class="mb-12">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="h-10 w-1 rounded-full bg-emerald-600"></div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        Recommended for You
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Based on your <span class="text-emerald-600 font-semibold">{{ auth()->user()->preferences }}</span> interests and schedule
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
            @foreach ($recommendedEvents as $event)
            @php
                $volunteersNeeded = max(0, $event->required_volunteers - $event->current_volunteers);
                $progress    = $event->required_volunteers > 0 ? round(($event->current_volunteers / $event->required_volunteers) * 100) : 0;
                $isFull      = $event->isFull();
                $isUrgent    = $volunteersNeeded > 0 && $volunteersNeeded <= 3;
                $isRegistered = auth()->check() && $event->isRegistered(auth()->id());
                $isOrganizer  = auth()->check() && $event->isOrganizer(auth()->id());
                $hasStarted  = $event->hasStarted();
                $hasEnded    = $event->hasEnded();
                $localDate   = $event->date->copy()->setTimezone(config('app.timezone'));
            @endphp

            <article class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">

                {{-- Image / Gradient Header --}}
                <div class="relative h-24 overflow-hidden">
                    @if (isset($event->match_percentage) && $event->match_percentage > 0)
                        <div class="absolute top-3 left-3 z-10">
                            <div class="glass-match-badge px-3 py-1.5 text-white text-[10px] font-black rounded-xl shadow-lg flex items-center gap-1.5">
                                <i class="bi bi-stars"></i>
                                <span>{{ $event->match_percentage }}% MATCH</span>
                            </div>
                        </div>
                    @endif

                    @if ($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}"
                             alt="{{ $event->title }}"
                             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-gray-50">
                            <i class="bi bi-calendar-event text-5xl text-gray-200"></i>
                        </div>
                    @endif

                    {{-- Status badge --}}
                    <div class="absolute top-3 right-3">
                        @if ($isOrganizer)
                            <span class="rounded-full bg-blue-600/90 backdrop-blur-sm px-3 py-1 text-[10px] font-bold text-white uppercase">Organizer</span>
                        @elseif ($isRegistered)
                            <span class="rounded-full bg-emerald-600/90 backdrop-blur-sm px-3 py-1 text-[10px] font-bold text-white uppercase">Joined</span>
                        @elseif ($isFull)
                            <span class="rounded-full bg-red-500/90 backdrop-blur-sm px-3 py-1 text-[10px] font-bold text-white uppercase">Full</span>
                        @elseif ($isUrgent)
                            <span class="rounded-full bg-orange-500/90 backdrop-blur-sm px-3 py-1 text-[10px] font-bold text-white uppercase">Urgent</span>
                        @elseif ($hasEnded)
                            <span class="rounded-full bg-gray-500/90 backdrop-blur-sm px-3 py-1 text-[10px] font-bold text-white uppercase">Closed</span>
                        @else
                            <span class="rounded-full bg-emerald-600/90 backdrop-blur-sm px-3 py-1 text-[10px] font-bold text-white uppercase">Open</span>
                        @endif
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="flex flex-1 flex-col p-3">

                    {{-- Date + Location row - Optimized Alignment --}}
                    <div class="mb-3 flex items-center gap-3 text-[11px] font-bold text-gray-400">
                        <span class="flex items-center gap-1.5 whitespace-nowrap bg-gray-50 px-2 py-1 rounded-md border border-gray-100">
                            <i class="bi bi-calendar3 text-emerald-500"></i>
                            {{ $localDate->format('M j, Y') }}
                        </span>
                        <div class="h-1 w-1 rounded-full bg-gray-200 flex-shrink-0"></div>
                        <span class="flex items-center gap-1.5 truncate">
                            <i class="bi bi-geo-alt text-emerald-500"></i>
                            {{ $event->location }}
                        </span>
                    </div>

                    {{-- Title + Description --}}
                    <h3 class="mb-2 line-clamp-1 text-lg font-black text-gray-900 group-hover:text-emerald-600 transition-colors leading-tight">
                        {{ $event->title }}
                    </h3>
                    <p class="mb-6 line-clamp-2 text-sm text-gray-500 leading-relaxed font-medium">
                        {{ $event->description }}
                    </p>

                    {{-- Progress bar - Enhanced Height --}}
                    <div class="mb-3 mt-auto">
                        <div class="mb-2 flex justify-between text-[10px] font-black uppercase tracking-widest text-gray-400">
                            <span class="flex items-center gap-1"><i class="bi bi-bar-chart-fill"></i> {{ $progress }}% Capacity</span>
                            <span class="text-gray-900">{{ $event->current_volunteers }}/{{ $event->required_volunteers }}</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 shadow-inner">
                            <div class="h-full rounded-full bg-emerald-500 transition-all duration-700 shadow-sm" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    {{-- Actions - Balanced Layout --}}
                    <div class="flex items-center gap-3 border-t border-gray-50 pt-3">
                        <a href="{{ route('events.show', $event) }}"
                           class="flex-1 rounded-xl bg-gray-900 px-4 py-3 text-center text-xs font-black text-white hover:bg-emerald-600 transition-all shadow-sm hover:shadow-emerald-200">
                            View Details
                        </a>
                        @if (!$isRegistered && !$isOrganizer && !$hasEnded && !$isFull)
                        <form action="{{ route('events.join', $event) }}" method="POST" class="contents">
                            @csrf
                            <button type="submit"
                                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm group/btn"
                                    title="Quick Join">
                                <i class="bi bi-person-plus text-lg group-hover/btn:scale-110 transition-transform"></i>
                            </button>
                        </form>
                        @elseif ($isRegistered)
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100" title="Already Joined">
                                <i class="bi bi-check-lg text-lg"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </section>
    @endif


    <!-- Global Opportunities Section -->
    <section class="mb-12">
        <div class="flex items-center gap-4 mb-8">
            <div class="h-10 w-1 rounded-full bg-gray-200"></div>
            <h2 class="text-2xl font-bold text-gray-900">Explore Opportunities</h2>
        </div>
        
        <main>
        @if ($events->count() > 0)
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
                @foreach ($events as $event)
                @php
                    $volunteersNeeded = max(0, $event->required_volunteers - $event->current_volunteers);
                    $progress = $event->required_volunteers > 0 ? round(($event->current_volunteers / $event->required_volunteers) * 100) : 0;
                    $isFull = $event->isFull();
                    $isUrgent = $volunteersNeeded > 0 && $volunteersNeeded <= 3;
                    $isRegistered = auth()->check() && $event->isRegistered(auth()->id());
                    $isOrganizer = auth()->check() && $event->isOrganizer(auth()->id());
                    $hasStarted = $event->hasStarted();
                    $hasEnded = $event->hasEnded();
                    $localDate = $event->date->copy()->setTimezone(config('app.timezone'));
                @endphp
                
                <article class="group relative flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="relative h-24 overflow-hidden">
                        @if ($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-gray-50">
                                <i class="bi bi-calendar3 text-4xl text-gray-200"></i>
                            </div>
                        @endif

                        <div class="absolute top-4 right-4">
                            @if ($isOrganizer)
                                <span class="rounded-lg bg-blue-50 px-2 py-1 text-[10px] font-bold text-blue-600 uppercase border border-blue-100">Organized</span>
                            @elseif ($isRegistered)
                                <span class="rounded-lg bg-emerald-50 px-2 py-1 text-[10px] font-bold text-emerald-600 uppercase border border-emerald-100">Joined</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-1 flex-col p-3">
                        <div class="mb-2">
                            <h3 class="mb-2 line-clamp-1 text-lg font-black text-gray-900 hover:text-emerald-600 transition-colors leading-tight">
                                {{ $event->title }}
                            </h3>
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide flex items-center gap-2 mb-3">
                                <span class="flex items-center gap-1.5 bg-gray-50 px-2 py-0.5 rounded border border-gray-100 whitespace-nowrap text-gray-500">
                                    <i class="bi bi-calendar3 text-emerald-500"></i>
                                    {{ $localDate->format('M j, Y') }}
                                </span>
                                <i class="bi bi-geo-alt text-emerald-600 ml-1"></i> {{ Str::limit($event->location, 25) }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <div class="flex justify-between text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">
                                <span>{{ $progress }}% Capacity</span>
                                <span class="text-gray-900">{{ $event->current_volunteers }}/{{ $event->required_volunteers }}</span>
                            </div>
                            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-emerald-600 shadow-sm transition-all duration-700" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <div class="mt-auto flex items-center justify-between pt-3 border-t border-gray-50">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Starting</span>
                                <span class="text-xs font-black text-gray-900">{{ $localDate->format('g:i A') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('events.show', $event) }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-900 text-white hover:bg-emerald-600 transition-all shadow-sm">
                                    <i class="bi bi-arrow-right-short text-2xl"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            @if ($events->hasPages())
            <div class="mt-12">
                <div class="flex flex-col sm:flex-row items-center justify-between rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                    <div class="text-xs font-bold text-gray-500 mb-4 sm:mb-0 uppercase tracking-widest">
                        Results: <span class="text-gray-900">{{ $events->firstItem() }}-{{ $events->lastItem() }}</span> / {{ $events->total() }}
                    </div>
                    <div>
                        {{ $events->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
            @endif

        @else
            <!-- Minimalist Empty State -->
            <div class="rounded-3xl bg-white px-8 py-20 text-center shadow-sm ring-1 ring-gray-100">
                <div class="mx-auto max-w-md">
                    <div class="mb-6 flex justify-center text-gray-200">
                        <i class="bi bi-search text-7xl"></i>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900">No opportunities found</h3>
                    <p class="mb-8 text-sm text-gray-500">
                        Try adjusting your search terms or filters to find more volunteer positions.
                    </p>
                    <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-8 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition-colors">
                        View All Feed
                    </a>
                </div>
            </div>
        @endif
    </main>
    </section>

</div>

<!-- Minimalist JavaScript & Logic -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Simple Entrance Animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const entranceObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('animate-in');
                }, index * 50);
                entranceObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('article').forEach((article) => {
        article.style.opacity = '0';
        article.style.transform = 'translateY(15px)';
        article.style.transition = 'all 0.5s ease-out';
        entranceObserver.observe(article);
    });

    const style = document.createElement('style');
    style.innerHTML = `
        .animate-in {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    `;
    document.head.appendChild(style);

    // 2. Efficient Search Logic
    const searchInput = document.getElementById('opportunitySearch');
    const cards = document.querySelectorAll('article');

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            let visibleCount = 0;

            cards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                const locationElem = card.querySelector('.bi-geo-alt-fill')?.nextElementSibling || 
                                    card.querySelector('.bi-geo-alt')?.nextElementSibling;
                const location = locationElem ? locationElem.textContent.toLowerCase() : '';
                
                const isMatch = title.includes(searchTerm) || 
                               description.includes(searchTerm) || 
                               location.includes(searchTerm);

                if (isMatch) {
                    card.style.display = 'flex';
                    setTimeout(() => card.classList.add('animate-in'), 10);
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                    card.classList.remove('animate-in');
                }
            });

            // Update UI Based on Search Results
            const exploreTitle = document.querySelector('section:last-of-type h2');
            if (exploreTitle) {
                exploreTitle.textContent = visibleCount === 0 ? "No opportunities match your search" : "Explore Opportunities";
            }
        });
    }
});
</script>

<style>
.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
}

.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
}

/* Professional Standard Transitions */
* {
    transition-property: color, background-color, border-color, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Minimalist Scrollbar */
::-webkit-scrollbar {
    width: 5px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}

/* Premium Match Badge Styles */
@keyframes float-glow {
    0%, 100% { transform: translateY(0); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2); }
    50% { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4); }
}

@keyframes flash-glow {
    0%, 100% { filter: brightness(1) drop-shadow(0 0 0px rgba(16, 185, 129, 0)); }
    50% { filter: brightness(1.2) drop-shadow(0 0 8px rgba(16, 185, 129, 0.5)); }
}

.glass-match-badge {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(20, 184, 166, 0.95) 100%);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    animation: float-glow 3s ease-in-out infinite, flash-glow 2s ease-in-out infinite;
    position: relative;
    overflow: hidden;
}

.glass-match-badge::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        45deg, 
        transparent 0%, 
        rgba(255, 255, 255, 0) 40%, 
        rgba(255, 255, 255, 0.3) 50%, 
        rgba(255, 255, 255, 0) 60%, 
        transparent 100%
    );
    transform: rotate(45deg);
    animation: shine-sweep 3s infinite;
}

@keyframes shine-sweep {
    0% { transform: translateX(-150%) rotate(45deg); }
    100% { transform: translateX(150%) rotate(45deg); }
}
</style>
@endsection
