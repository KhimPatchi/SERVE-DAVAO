@extends('layouts.sidebar.sidebar')

@section('title', 'Volunteer Opportunities - ServeDavao')

@section('content')
<div class="min-h-screen bg-gray-50/60 p-6">
    
    <!-- Search and Filter Section -->
    <section class="mb-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900">Available Positions</h2>
                    <p class="text-gray-600">Browse and apply for volunteer opportunities</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search opportunities..." 
                               class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 w-64 text-sm bg-white transition-all"
                               id="opportunitySearch">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    
                    <!-- Filter Dropdown -->
                    <div class="relative">
                        <select class="appearance-none pl-4 pr-10 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-sm transition-all cursor-pointer">
                            <option>All Opportunities</option>
                            <option>Urgent Needs</option>
                            <option>Near My Location</option>
                            <option>Starting Soon</option>
                        </select>
                        <i class="bi bi-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                    
                    <!-- Sort Dropdown -->
                    <div class="relative">
                        <select class="appearance-none pl-4 pr-10 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-sm transition-all cursor-pointer">
                            <option>Newest First</option>
                            <option>Most Volunteers Needed</option>
                            <option>Starting Soonest</option>
                            <option>Closest Location</option>
                        </select>
                        <i class="bi bi-sort-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Volunteer Opportunities Grid -->
    <main>
        @if($events->count() > 0)
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach($events as $event)
                @php
                    $volunteersNeeded = max(0, $event->required_volunteers - $event->current_volunteers);
                    $progress = $event->required_volunteers > 0 
                        ? round(($event->current_volunteers / $event->required_volunteers) * 100)
                        : 0;
                    $isFull = $volunteersNeeded === 0;
                    $isUrgent = $volunteersNeeded > 0 && $volunteersNeeded <= 3;
                    $isRegistered = auth()->check() && $event->isRegistered(auth()->id());
                    $isOrganizer = auth()->check() && $event->isOrganizer(auth()->id());
                @endphp
                
                <article class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition-all duration-500 hover:shadow-xl hover:translate-y-[-2px]">
                    <!-- Status Badge -->
                    <div class="absolute top-4 right-4">
                        @if($isOrganizer)
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 ring-1 ring-blue-200">
                                <i class="bi bi-person-gear mr-1"></i>
                                Organizer
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium 
                                {{ $isFull ? 'bg-red-100 text-red-800 ring-1 ring-red-200' : 
                                   ($isUrgent ? 'bg-orange-100 text-orange-800 ring-1 ring-orange-200' : 
                                   'bg-green-100 text-green-800 ring-1 ring-green-200') }}">
                                <i class="bi {{ $isFull ? 'bi-x-circle' : ($isUrgent ? 'bi-exclamation-triangle' : 'bi-check-circle') }} mr-1"></i>
                                {{ $isFull ? 'Full' : ($isUrgent ? 'Urgent' : 'Available') }}
                            </span>
                        @endif
                    </div>

                    <!-- Event Header -->
                    <div class="mb-4 pr-16">
                        <h3 class="mb-3 line-clamp-2 text-lg font-bold text-gray-900 group-hover:text-green-600 transition-colors leading-tight">
                            {{ $event->title }}
                        </h3>
                        <p class="line-clamp-2 text-gray-600 text-sm leading-relaxed">
                            {{ Str::limit($event->description, 120) }}
                        </p>
                    </div>

                    <!-- Event Metadata -->
                    <div class="mb-4 space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="bi bi-geo-alt mr-3 text-base text-green-500"></i>
                            <span class="line-clamp-1 font-medium">{{ $event->location }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="bi bi-calendar-event mr-3 text-base text-green-500"></i>
                            <div class="flex flex-col">
                                <span class="font-medium">{{ $event->date->format('M j, Y') }}</span>
                                <span class="text-xs text-gray-500">{{ $event->date->format('g:i A') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="bi bi-person-check mr-3 text-base text-green-500"></i>
                                <span class="font-medium">{{ $event->organizer->name ?? 'Community Organizer' }}</span>
                            </div>
                            <div class="flex items-center text-xs text-gray-500 bg-gray-50 rounded-lg px-2 py-1">
                                <i class="bi bi-clock mr-1"></i>
                                <span>{{ $event->date->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Volunteer Capacity Analytics -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between text-xs text-gray-600 mb-2">
                            <span>Volunteer Capacity</span>
                            <span class="font-semibold">{{ $event->current_volunteers }}/{{ $event->required_volunteers }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-200 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-green-500 to-teal-500 transition-all duration-1000 ease-out" 
                                 style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="mt-1 flex justify-between text-xs">
                            <span class="text-gray-500">{{ $progress }}% filled</span>
                            <span class="font-medium text-gray-700">{{ $volunteersNeeded }} spots available</span>
                        </div>
                    </div>

                    <!-- Action Panel -->
                    <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                        <div class="flex items-center gap-2">
                            @if($isOrganizer)
                                <span class="inline-flex items-center text-xs text-blue-600 font-medium">
                                    <i class="bi bi-person-gear mr-1"></i>
                                    You organized this
                                </span>
                            @elseif($isRegistered)
                                <span class="inline-flex items-center text-xs text-green-600 font-medium">
                                    <i class="bi bi-check-circle mr-1"></i>
                                    Registered
                                </span>
                            @elseif($isFull)
                                <span class="inline-flex items-center text-xs text-red-600 font-medium">
                                    <i class="bi bi-x-circle mr-1"></i>
                                    Event Full
                                </span>
                            @else
                                <span class="inline-flex items-center text-xs text-blue-600 font-medium">
                                    <i class="bi bi-person-plus mr-1"></i>
                                    {{ $volunteersNeeded }} spots left
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-2">
                            @if($isOrganizer)
                                <span class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-800 text-sm font-medium rounded-lg">
                                    <i class="bi bi-person-gear mr-1.5"></i>
                                    Organizer
                                </span>
                            @elseif($isRegistered)
                                <span class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-800 text-sm font-medium rounded-lg">
                                    <i class="bi bi-check-lg mr-1.5"></i>
                                    Joined
                                </span>
                            @elseif($isFull)
                                <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg">
                                    <i class="bi bi-x-lg mr-1.5"></i>
                                    Full
                                </span>
                            @else
                                <form action="{{ route('events.join', $event) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="group/btn inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-green-600 to-teal-600 px-4 py-2.5 text-sm font-semibold text-white transition-all hover:shadow-lg hover:scale-105">
                                        <i class="bi bi-person-plus transition-transform group-hover/btn:scale-110"></i>
                                        Volunteer
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('events.show', $event) }}" 
                               class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50 hover:border-gray-400">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            <!-- Professional Pagination -->
            @if($events->hasPages())
            <div class="mt-8">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }} opportunities
                    </div>
                    <div class="flex items-center gap-2">
                        {{ $events->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
            @endif

        @else
            <!-- Professional Empty State -->
            <div class="rounded-2xl bg-white p-16 text-center shadow-sm ring-1 ring-gray-100">
                <div class="mx-auto max-w-lg">
                    <div class="mb-6 inline-flex rounded-2xl bg-gradient-to-r from-green-50 to-teal-50 p-8">
                        <i class="bi bi-calendar-x text-5xl text-green-500"></i>
                    </div>
                    <h3 class="mb-4 text-2xl font-bold text-gray-900">No Volunteer Opportunities Available</h3>
                    <p class="mb-8 text-lg text-gray-600 leading-relaxed">
                        There are currently no volunteer opportunities available. New opportunities will appear here once organizers create events.
                    </p>
                    <div class="space-y-4">
                        <a href="{{ route('events.index') }}" 
                           class="inline-flex items-center gap-3 rounded-xl bg-gradient-to-r from-green-600 to-teal-600 px-8 py-4 text-lg font-semibold text-white transition-all hover:shadow-lg hover:scale-105">
                            <i class="bi bi-calendar-week"></i>
                            Browse All Events
                        </a>
                        <div class="text-sm text-gray-500">
                            <i class="bi bi-info-circle mr-1"></i>
                            Volunteer opportunities are created by verified community organizers
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <!-- Quick Actions Section -->
    <section class="mt-8">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center gap-2 rounded-xl bg-gray-600 px-4 py-3 text-sm font-semibold text-white transition-all hover:bg-gray-700 hover:shadow-lg">
                    <i class="bi bi-arrow-left"></i>
                    Back to Dashboard
                </a>
                <a href="{{ route('volunteers.my-events') }}" 
                   class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition-all hover:bg-blue-700 hover:shadow-lg">
                    <i class="bi bi-list-ul"></i>
                    My Registered Events
                </a>
                @if(Auth::user()->is_organizer)
                <a href="{{ route('volunteers.organized-events') }}" 
                   class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-4 py-3 text-sm font-semibold text-white transition-all hover:bg-purple-700 hover:shadow-lg">
                    <i class="bi bi-building"></i>
                    My Organized Events
                </a>
                @endif
            </div>
        </div>
    </section>

</div>

<!-- Professional JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time search functionality
    const searchInput = document.getElementById('opportunitySearch');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = e.target.value.toLowerCase();
                const opportunityCards = document.querySelectorAll('article');
                let visibleCount = 0;
                
                opportunityCards.forEach(card => {
                    const title = card.querySelector('h3').textContent.toLowerCase();
                    const description = card.querySelector('p').textContent.toLowerCase();
                    const location = card.querySelector('[class*="line-clamp-1"]').textContent.toLowerCase();
                    
                    const isVisible = title.includes(searchTerm) || 
                                    description.includes(searchTerm) || 
                                    location.includes(searchTerm);
                    
                    card.style.display = isVisible ? 'block' : 'none';
                    if (isVisible) visibleCount++;
                });

                // Update results counter
                const resultsCounter = document.querySelector('[class*="text-gray-600"]');
                if (resultsCounter && visibleCount > 0) {
                    resultsCounter.textContent = `Showing ${visibleCount} of ${opportunityCards.length} opportunities`;
                }
            }, 300);
        });
    }

    // Smooth scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe opportunity cards for scroll animations
    document.querySelectorAll('article').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.6s ease, transform 0.6s ease`;
        card.style.transitionDelay = `${index * 0.1}s`;
        observer.observe(card);
    });
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

/* Professional smooth transitions */
* {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Custom scrollbar for webkit */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endsection