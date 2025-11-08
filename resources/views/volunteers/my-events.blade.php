@extends('layouts.sidebar.sidebar')

@section('content')
<div class="min-h-screen bg-gray-50/60 p-6" id="my-events">
    
    <!-- Page Header with Breadcrumb -->
    <header class="mb-8">
        <nav class="mb-4 flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('dashboard') }}" class="transition-colors hover:text-gray-700">Dashboard</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-gray-800 font-medium">My Events</span>
        </nav>
        
        <div class="flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 tracking-tight">My Registered Events</h1>
                    <p class="mt-3 text-xl text-gray-600 max-w-2xl">Track your volunteer journey and manage upcoming commitments</p>
                </div>
                <div class="hidden lg:flex items-center gap-3">
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-4 py-2 text-sm font-medium text-blue-800">
                        <i class="bi bi-person-check mr-2"></i>
                        Active Volunteer
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Statistics Overview -->
    <section class="mb-8">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <!-- Total Events Card -->
            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:shadow-lg hover:ring-2 hover:ring-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Total Registered</p>
                        <h2 class="mt-2 text-3xl font-bold text-gray-900">{{ $events->total() }}</h2>
                        <p class="mt-1 text-xs text-gray-500">All time events</p>
                    </div>
                    <div class="rounded-2xl bg-blue-500/10 p-4">
                        <i class="bi bi-calendar-event text-2xl text-blue-600"></i>
                    </div>
                </div>
                <div class="absolute -right-6 -top-6 h-16 w-16 rounded-full bg-blue-100/40"></div>
            </div>

            <!-- Upcoming Events Card -->
            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:shadow-lg hover:ring-2 hover:ring-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Upcoming</p>
                        <h2 class="mt-2 text-3xl font-bold text-gray-900">{{ $upcomingCount ?? 0 }}</h2>
                        <p class="mt-1 text-xs text-gray-500">Future commitments</p>
                    </div>
                    <div class="rounded-2xl bg-green-500/10 p-4">
                        <i class="bi bi-clock text-2xl text-green-600"></i>
                    </div>
                </div>
                <div class="absolute -right-6 -top-6 h-16 w-16 rounded-full bg-green-100/40"></div>
            </div>

            <!-- Completed Events Card -->
            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:shadow-lg hover:ring-2 hover:ring-purple-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Completed</p>
                        <h2 class="mt-2 text-3xl font-bold text-gray-900">{{ $completedCount ?? 0 }}</h2>
                        <p class="mt-1 text-xs text-gray-500">Past events</p>
                    </div>
                    <div class="rounded-2xl bg-purple-500/10 p-4">
                        <i class="bi bi-check-circle text-2xl text-purple-600"></i>
                    </div>
                </div>
                <div class="absolute -right-6 -top-6 h-16 w-16 rounded-full bg-purple-100/40"></div>
            </div>

            <!-- Hours Contributed Card -->
            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:shadow-lg hover:ring-2 hover:ring-orange-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Hours Contributed</p>
                        <h2 class="mt-2 text-3xl font-bold text-gray-900">{{ $totalHours ?? 0 }}</h2>
                        <p class="mt-1 text-xs text-gray-500">Total impact</p>
                    </div>
                    <div class="rounded-2xl bg-orange-500/10 p-4">
                        <i class="bi bi-award text-2xl text-orange-600"></i>
                    </div>
                </div>
                <div class="absolute -right-6 -top-6 h-16 w-16 rounded-full bg-orange-100/40"></div>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
        <!-- Main Content - Events -->
        <main class="lg:col-span-3">
            <!-- Events Header with Quick Actions -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Your Event Registrations</h2>
                    <p class="text-gray-600">Active and upcoming volunteer commitments</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50">
                        <i class="bi bi-filter"></i>
                        Filter
                    </button>
                    <button class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50">
                        <i class="bi bi-sort-down"></i>
                        Sort
                    </button>
                </div>
            </div>

            <!-- Quick Actions & Progress Section -->
            <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Quick Actions -->
                <div class="rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-6 text-white shadow-lg">
                    <h3 class="mb-4 text-lg font-semibold">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('volunteers') }}" 
                           class="flex w-full items-center justify-between rounded-xl bg-white/20 px-4 py-3 text-sm font-medium backdrop-blur-sm transition-all hover:bg-white/30 hover:shadow-lg">
                            <span>Find Opportunities</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="flex w-full items-center justify-between rounded-xl bg-white/20 px-4 py-3 text-sm font-medium backdrop-blur-sm transition-all hover:bg-white/30 hover:shadow-lg">
                            <span>Back to Dashboard</span>
                            <i class="bi bi-grid"></i>
                        </a>
                    </div>
                </div>

                <!-- Progress Summary -->
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Your Progress</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="text-gray-600">Completion Rate</span>
                                <span class="font-semibold text-gray-900">85%</span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-200">
                                <div class="h-full w-4/5 rounded-full bg-green-500"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Avg. Rating</span>
                            <span class="flex items-center font-semibold text-yellow-600">
                                <i class="bi bi-star-fill mr-1"></i>4.8
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Community Rank</span>
                            <span class="font-semibold text-gray-900">Top 15%</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($events->count() > 0)
                <!-- Events Grid -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    @foreach($events as $event)
                    <article class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition-all duration-500 hover:shadow-xl hover:ring-2 hover:ring-blue-200">
                        <!-- Status Ribbon -->
                        <div class="absolute -right-8 top-6 rotate-45 bg-green-500 px-8 py-1 text-xs font-semibold text-white shadow-lg">
                            Registered
                        </div>

                        <!-- Event Header -->
                        <div class="mb-4 pr-8">
                            <h3 class="mb-3 line-clamp-2 text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                {{ $event->title }}
                            </h3>
                            <p class="line-clamp-2 text-gray-600 leading-relaxed">
                                {{ Str::limit($event->description, 100) }}
                            </p>
                        </div>

                        <!-- Event Details -->
                        <div class="mb-6 space-y-3">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="bi bi-geo-alt mr-3 text-lg text-blue-500"></i>
                                <span class="line-clamp-1 font-medium">{{ $event->location }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="bi bi-calendar-event mr-3 text-lg text-blue-500"></i>
                                <span class="font-medium">{{ $event->date->format('M j, Y') }}</span>
                                <span class="mx-2 text-gray-400">•</span>
                                <span class="text-gray-500">{{ $event->date->format('g:i A') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="bi bi-people mr-3 text-lg text-blue-500"></i>
                                    <span>{{ $event->current_volunteers }}/{{ $event->required_volunteers }} volunteers</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="bi bi-clock mr-1"></i>
                                    <span>{{ $event->date->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="mb-2 flex items-center justify-between text-xs">
                                <span class="text-gray-600">Volunteer Progress</span>
                                <span class="font-semibold text-gray-900">{{ round(($event->current_volunteers / $event->required_volunteers) * 100) }}%</span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-200">
                                <div class="h-full rounded-full bg-blue-500" style="width: {{ ($event->current_volunteers / $event->required_volunteers) * 100 }}%"></div>
                            </div>
                        </div>

                        <!-- Event Actions -->
                        <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                                    <i class="bi bi-person-check mr-1"></i>
                                    Confirmed
                                </span>
                            </div>
                            
                            <a href="{{ route('events.show', $event) }}" 
                               class="group/btn inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition-all hover:bg-blue-700 hover:shadow-lg">
                                <i class="bi bi-eye transition-transform group-hover/btn:scale-110"></i>
                                View Details
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($events->hasPages())
                <div class="mt-8">
                    {{ $events->links() }}
                </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="rounded-2xl bg-white p-12 text-center shadow-sm ring-1 ring-gray-100">
                    <div class="mx-auto max-w-md">
                        <div class="mb-6 inline-flex rounded-2xl bg-blue-50 p-6">
                            <i class="bi bi-calendar-x text-4xl text-blue-500"></i>
                        </div>
                        <h3 class="mb-3 text-2xl font-bold text-gray-900">No Registered Events</h3>
                        <p class="mb-8 text-lg text-gray-600 leading-relaxed">You haven't registered for any events yet. Start making an impact in your community today!</p>
                        <div class="space-y-4">
                            <a href="{{ route('volunteers') }}" 
                               class="inline-flex items-center gap-3 rounded-xl bg-blue-600 px-8 py-4 text-lg font-semibold text-white transition-all hover:bg-blue-700 hover:shadow-lg">
                                <i class="bi bi-search"></i>
                                Explore Volunteer Opportunities
                            </a>
                            <p class="text-sm text-gray-500">Discover meaningful ways to contribute to your community</p>
                        </div>
                    </div>
                </div>
            @endif
        </main>

        <!-- Empty Sidebar (for future use or can be removed) -->
        <aside class="lg:col-span-1">
            <!-- This space can be used for additional widgets, notifications, or can be left empty -->
            <div class="sticky top-6">
                <div class="rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 p-6 text-center">
                    <i class="bi bi-lightbulb text-3xl text-gray-400 mb-3"></i>
                    <p class="text-sm text-gray-600">Space for additional features or announcements</p>
                </div>
            </div>
        </aside>
    </div>

</div>

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

/* Smooth transitions for all interactive elements */
* {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}
</style>
@endsection