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
    <div class="grid grid-cols-1 gap-8">
        <!-- Main Content - Events -->
        <main>
            <!-- Events Header with Quick Actions -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Upcoming & Active Commitments</h2>
                    <p class="text-gray-600">Manage your current and future volunteer engagements</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="#event-history" class="flex items-center gap-2 rounded-lg border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-medium text-purple-700 transition-all hover:bg-purple-100">
                        <i class="bi bi-clock-history"></i>
                        History
                    </a>
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

            <!-- Progress Summary Only (Quick Actions Removed) -->
            <div class="mb-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Your Volunteer Progress</h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="text-gray-600">Completion Rate</span>
                                <span class="font-semibold text-gray-900">
                                    @php
                                        $completionRate = ($events->total() > 0 && isset($completedCount)) 
                                            ? round(($completedCount / $events->total()) * 100) 
                                            : 0;
                                    @endphp
                                    {{ $completionRate }}%
                                </span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-200">
                                <div class="h-full rounded-full bg-green-500" 
                                     style="width: {{ $completionRate }}%"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Hours Contributed</span>
                            <span class="flex items-center font-semibold text-orange-600">
                                <i class="bi bi-clock mr-1"></i>{{ $totalHours ?? 0 }}h
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Active Events</span>
                            <span class="font-semibold text-gray-900">{{ $upcomingCount ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($events->count() > 0)
                <!-- Events Grid -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    @foreach($events as $event)
                               @php
// SIMPLIFIED: Use the model's computed properties
$isOngoing = $event->is_ongoing;
$isUpcoming = $event->is_upcoming;
$isCompleted = $event->is_completed;

// Status configuration
$statusConfig = [
    'ongoing' => ['class' => 'bg-green-500', 'text' => 'Ongoing', 'badge_class' => 'bg-green-100 text-green-800'],
    'today' => ['class' => 'bg-blue-500', 'text' => 'Today', 'badge_class' => 'bg-blue-100 text-blue-800'],
    'upcoming' => ['class' => 'bg-orange-500', 'text' => 'Upcoming', 'badge_class' => 'bg-orange-100 text-orange-800'],       
    'completed' => ['class' => 'bg-purple-500', 'text' => 'Completed', 'badge_class' => 'bg-purple-100 text-purple-800'],     
];

// Determine status
if ($isOngoing) {
    $status = $statusConfig['ongoing'];
} elseif ($event->date->isToday() && $isUpcoming) {
    $status = $statusConfig['today'];
} elseif ($isUpcoming) {
    $status = $statusConfig['upcoming'];
} else {
    $status = $statusConfig['completed'];
}

// Time progress calculation
if ($isOngoing) {
    $timeProgress = 100;
} elseif ($isUpcoming && $event->date->isToday()) {
    $eventStart = $event->date;
    $hoursUntilEvent = $eventStart->diffInHours(now());
    $timeProgress = max(0, min(100, (24 - $hoursUntilEvent) / 24 * 100));
} elseif ($isUpcoming) {
    $eventStart = $event->date;
    $daysUntilEvent = $eventStart->diffInDays(now());
    $maxDaysToShow = 14;
    $timeProgress = max(0, min(100, (($maxDaysToShow - $daysUntilEvent) / $maxDaysToShow) * 100));
} else {
    $timeProgress = 100;
}
@endphp
                    
                    <article class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition-all duration-500 hover:shadow-xl hover:ring-2 hover:ring-blue-200">
                        <!-- FIXED: Dynamic Status Ribbon -->
                        <div class="absolute -right-8 top-6 rotate-45 {{ $status['class'] }} px-8 py-1 text-xs font-semibold text-white shadow-lg">
                            {{ $status['text'] }}
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
                                    <i class="bi bi-person-check mr-3 text-lg text-blue-500"></i>
                                    <span>You're Registered</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="bi bi-clock mr-1"></i>
                                    <span>
                                        @if($status['text'] === 'Ongoing')
                                            happening now
                                        @elseif($isUpcoming && $isToday)
                                            today at {{ $event->date->format('g:i A') }}
                                        @elseif($isUpcoming)
                                            in {{ $event->date->diffForHumans(now(), true) }}
                                        @else
                                            {{ $event->date->diffForHumans() }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- FIXED: Time Progress Bar -->
                        <div class="mb-4">
                            <div class="mb-2 flex items-center justify-between text-xs">
                                <span class="text-gray-600">
                                    @if($status['text'] === 'Ongoing')
                                        Event in progress
                                    @elseif($isUpcoming)
                                        Time until event
                                    @elseif($isCompleted)
                                        Event completed
                                    @else
                                        Happening today
                                    @endif
                                </span>
                                <span class="font-semibold text-gray-900">
                                    @if($isUpcoming)
                                        {{ round($timeProgress) }}%
                                    @else
                                        100%
                                    @endif
                                </span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-200">
                                @if($isUpcoming)
                                    <div class="h-full rounded-full bg-blue-500 transition-all duration-1000" style="width: {{ $timeProgress }}%"></div>
                                @elseif($isCompleted)
                                    <div class="h-full rounded-full bg-purple-500" style="width: 100%"></div>
                                @else
                                    <div class="h-full rounded-full bg-green-500 animate-pulse" style="width: 100%"></div>
                                @endif
                            </div>
                        </div>

                        <!-- Event Actions -->
                        <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center rounded-full {{ $status['badge_class'] }} px-3 py-1 text-xs font-medium">
                                    <i class="bi bi-person-check mr-1"></i>
                                    {{ $status['text'] }}
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

                <!-- EVENT HISTORY SECTION (Always Visible) -->
                <div id="event-history" class="mt-16 pt-8 border-t border-gray-200 scroll-mt-24">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <i class="bi bi-clock-history text-2xl text-purple-600"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Event History</h2>
                            <p class="text-gray-600">Your completed missions and impact</p>
                        </div>
                    </div>

                    @if(isset($attendedEvents) && $attendedEvents->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50 text-gray-600 font-medium">
                                    <tr>
                                        <th class="px-6 py-4">Event Name</th>
                                        <th class="px-6 py-4">Date & Time</th>
                                        <th class="px-6 py-4">Organizer</th>
                                        <th class="px-6 py-4 text-center">Hours Earned</th>
                                        <th class="px-6 py-4 text-center">Status</th>
                                        <th class="px-6 py-4 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($attendedEvents as $history)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900">{{ $history->title }}</div>
                                            <div class="text-xs text-gray-500 truncate max-w-[200px]">{{ $history->location }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-900">{{ $history->date->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $history->date->format('g:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-[10px] font-bold text-blue-600">
                                                    {{ substr($history->organizer->name ?? 'S', 0, 1) }}
                                                </div>
                                                <span class="text-gray-700">{{ $history->organizer->name ?? 'ServeDavao' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center gap-1 font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded-md">
                                                <i class="bi bi-lightning-charge-fill text-xs"></i>
                                                {{ $history->pivot->hours_volunteered ?? 0 }}h
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">
                                                Completed
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('events.show', $history) }}" class="text-blue-600 hover:text-blue-800 font-medium text-xs hover:underline">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="rounded-2xl bg-gray-50 p-8 text-center border border-dashed border-gray-300">
                        <div class="inline-flex rounded-full bg-gray-100 p-4 mb-4">
                            <i class="bi bi-clock-history text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No History Yet</h3>
                        <p class="text-gray-500 mt-1">Events you complete will appear here automatically.</p>
                    </div>
                    @endif
                </div>

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