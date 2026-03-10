@extends ('layouts.sidebar.sidebar')

@section ('content')
<div class="min-h-screen bg-gray-50/60 p-6" id="my-events" x-data="{ activeTab: 'upcoming', showLeaveModal: false, leavingEventId: null, leavingEventTitle: '' }">
    
    <!-- Page Header with Breadcrumb -->
    <header class="mb-8">
        <nav class="mb-4 flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('dashboard') }}" class="transition-colors hover:text-gray-700">Dashboard</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-gray-800 font-medium">My Events</span>
        </nav>
        
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 tracking-tight">My Volunteer Hub</h1>
                <p class="mt-2 text-gray-600">Track and manage your community impact</p>
            </div>
            
            <!-- Quick Search & Actions -->
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <form action="{{ route('volunteers.my-events') }}" method="GET" class="relative group w-full sm:w-80">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search your events..." 
                           class="w-full pl-11 pr-4 py-3 rounded-2xl border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-sm">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    @if (request('search'))
                        <a href="{{ route('volunteers.my-events') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                </form>

                <div class="inline-flex p-1 bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 w-full sm:w-auto">
                    <button @click="activeTab = 'upcoming'" 
                            :class="activeTab === 'upcoming' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50'"
                            class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300">
                        Upcoming
                    </button>
                    <button @click="activeTab = 'history'" 
                            :class="activeTab === 'history' ? 'bg-purple-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50'"
                            class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300">
                        History
                    </button>
                </div>
            </div>
        </div>
    </header>

    @php
        $completionRate = ($events->total() > 0 && isset($completedCount)) 
            ? round(($completedCount / $events->total()) * 100) 
            : 0;
    @endphp

    <div class="grid grid-cols-1 gap-8">
        <main>
            <!-- Tab Content: Upcoming -->
            <div x-show="activeTab === 'upcoming'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                
                @if ($events->total() > 0)
                <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="md:col-span-2 rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-100 border-l-8 border-green-500">
                        <div class="flex items-center gap-6">
                            <div class="hidden sm:flex h-16 w-16 items-center justify-center rounded-2xl bg-green-50">
                                <i class="bi bi-star-fill text-2xl text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Impact Milestones</h3>
                                <div class="flex items-center justify-between text-sm mb-2">
                                    <span class="text-gray-500">Goal Completion Rate</span>
                                    <span class="font-bold text-green-600">{{ $completionRate }}%</span>
                                </div>
                                <div class="h-3 rounded-full bg-gray-100 overflow-hidden">
                                    <div class="h-full bg-green-500 rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(34,197,94,0.3)]" style="width: {{ $completionRate }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-3xl bg-gradient-to-br from-blue-600 to-indigo-700 p-8 shadow-lg shadow-blue-500/20 text-white">
                        <div class="flex flex-col h-full justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium uppercase tracking-wider mb-1">Lifetime Contribution</p>
                                <h4 class="text-3xl font-bold">{{ $totalHours ?? 0 }}h</h4>
                            </div>
                            <div class="mt-4 flex items-center gap-2 text-xs text-blue-100">
                                <i class="bi bi-award-fill text-lg"></i>
                                <span>Certified Impact Hours</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            @if ($events->count() > 0)
                <!-- Events Grid -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    @foreach ($events as $event)
                               @php
// SIMPLIFIED: Use the model's computed properties
$isOngoing   = $event->is_ongoing;
$isUpcoming  = $event->is_upcoming;
$isCompleted = $event->is_completed;
$isToday     = $event->date->isToday();

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
                                <span class="mx-2 text-gray-400">â€¢</span>
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
                                        @if ($status['text'] === 'Ongoing')
                                            happening now
                                        @elseif ($isUpcoming && $isToday)
                                            today at {{ $event->date->format('g:i A') }}
                                        @elseif ($isUpcoming)
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
                                    @if ($status['text'] === 'Ongoing')
                                        Event in progress
                                    @elseif ($isUpcoming)
                                        Time until event
                                    @elseif ($isCompleted)
                                        Event completed
                                    @else
                                        Happening today
                                    @endif
                                </span>
                                <span class="font-semibold text-gray-900">
                                    @if ($isUpcoming)
                                        {{ round($timeProgress) }}%
                                    @else
                                        100%
                                    @endif
                                </span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-200">
                                @if ($isUpcoming)
                                    <div class="h-full rounded-full bg-blue-500 transition-all duration-1000" style="width: {{ $timeProgress }}%"></div>
                                @elseif ($isCompleted)
                                    <div class="h-full rounded-full bg-purple-500" style="width: 100%"></div>
                                @else
                                    <div class="h-full rounded-full bg-green-500 animate-pulse" style="width: 100%"></div>
                                @endif
                            </div>
                        </div>

                        {{-- Event Actions --}}
                        <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center rounded-full {{ $status['badge_class'] }} px-3 py-1 text-xs font-medium">
                                    <i class="bi bi-person-check mr-1"></i>
                                    {{ $status['text'] }}
                                </span>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                {{-- View Ticket (QR) â€” shown only for upcoming/ongoing events --}}
                                @if ($isUpcoming || $isOngoing)
                                <button type="button"
                                        @click="showLeaveModal = true; leavingEventId = {{ $event->id }}; leavingEventTitle = '{{ addslashes($event->title) }}'"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition-all hover:bg-red-100"
                                        title="Cancel your registration">
                                    <i class="bi bi-person-x"></i>
                                    Leave
                                </button>

                                <button type="button"
                                        onclick="openTicketModal({{ $event->id }})"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition-all hover:bg-emerald-100 hover:shadow-sm"
                                        title="View your QR check-in ticket">
                                    <i class="bi bi-qr-code"></i>
                                    My Ticket
                                </button>
                                @endif

                                <a href="{{ route('events.show', $event) }}" 
                                   class="group/btn inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition-all hover:bg-blue-700 hover:shadow-lg">
                                    <i class="bi bi-eye transition-transform group-hover/btn:scale-110"></i>
                                    Details
                                </a>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($events->hasPages())
                <div class="mt-8">
                    {{ $events->links() }}
                </div>
                @endif
                
                @else
                    <!-- Empty State for Search or No Events -->
                    <div class="rounded-[3rem] bg-white p-16 text-center shadow-sm ring-1 ring-gray-100">
                        <div class="mx-auto max-w-md">
                            <div class="mb-8 inline-flex rounded-3xl bg-blue-50 p-8">
                                <i class="bi bi-calendar2-x text-5xl text-blue-500"></i>
                            </div>
                            <h3 class="mb-3 text-2xl font-bold text-gray-900">
                                {{ request('search') ? 'No Matches Found' : 'No Active Commitments' }}
                            </h3>
                            <p class="mb-8 text-gray-500 leading-relaxed">
                                {{ request('search') 
                                    ? "We couldn't find any events matching '" . request('search') . "'. Try a different keyword." 
                                    : "You haven't registered for any upcoming events yet. Start your journey today!" }}
                            </p>
                            <a href="{{ route('volunteers') }}" class="inline-flex items-center gap-3 rounded-2xl bg-blue-600 px-8 py-4 text-lg font-bold text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all">
                                <i class="bi bi-search"></i>
                                Browse Opportunities
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Tab Content: History -->
            <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="bi bi-clock-history text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Event History</h2>
                        <p class="text-gray-600">Your completed missions and impact</p>
                    </div>
                </div>

                @if (isset($attendedEvents) && $attendedEvents->count() > 0)
                    <div class="bg-white rounded-3xl shadow-sm ring-1 ring-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50/50 text-gray-500 font-semibold border-b border-gray-100">
                                    <tr>
                                        <th class="px-8 py-5">Event Name</th>
                                        <th class="px-8 py-5">Date & Time</th>
                                        <th class="px-8 py-5">Organizer</th>
                                        <th class="px-8 py-5 text-center">Hours</th>
                                        <th class="px-8 py-5 text-center">Status</th>
                                        <th class="px-8 py-5 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($attendedEvents as $history)
                                    <tr class="hover:bg-blue-50/10 transition-colors group">
                                        <td class="px-8 py-5">
                                            <div class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $history->title }}</div>
                                            <div class="text-xs text-gray-400 mt-1 flex items-center">
                                                <i class="bi bi-geo-alt mr-1"></i>
                                                {{ Str::limit($history->location, 30) }}
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="font-medium text-gray-900">{{ $history->date->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ $history->date->format('g:i A') }}</div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-[10px] font-bold text-indigo-700 border border-indigo-200">
                                                    {{ substr($history->organizer->name ?? 'S', 0, 1) }}
                                                </div>
                                                <span class="font-medium text-gray-700">{{ $history->organizer->name ?? 'ServeDavao' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <span class="inline-flex items-center gap-1 font-bold text-orange-600 bg-orange-50 px-3 py-1 rounded-xl ring-1 ring-orange-200">
                                                <i class="bi bi-lightning-charge-fill text-[10px]"></i>
                                                {{ $history->pivot->hours_volunteered ?? 0 }}h
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            @php
                                                $pivotStatus = strtolower($history->pivot->status ?? 'completed');
                                                $statusColor = 'bg-purple-100 text-purple-800 ring-purple-200';
                                                $statusText = 'Completed';
                                                
                                                if ($history->is_upcoming && $pivotStatus !== 'attended') {
                                                    $statusColor = 'bg-blue-100 text-blue-800 ring-blue-200';
                                                    $statusText = 'Registered';
                                                } elseif ($pivotStatus === 'attended') {
                                                    $statusColor = 'bg-green-100 text-green-800 ring-green-200';
                                                    $statusText = 'Attended';
                                                }
                                            @endphp
                                            <span class="inline-flex items-center rounded-full {{ $statusColor }} px-3 py-1 text-[10px] font-bold uppercase tracking-wider ring-1">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <a href="{{ route('events.show', $history) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gray-50 text-gray-600 hover:bg-blue-600 hover:text-white font-bold text-xs transition-all shadow-sm">
                                                View
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="rounded-3xl bg-white p-16 text-center ring-1 ring-gray-100 shadow-sm border-2 border-dashed border-gray-200">
                        <div class="inline-flex rounded-3xl bg-gray-50 p-8 mb-6">
                            <i class="bi bi-clock-history text-5xl text-gray-300"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Clean Slate</h3>
                        <p class="text-gray-500 mt-2 max-w-sm mx-auto">Complete your first mission to start building your volunteer legacy and earning impact hours.</p>
                    </div>
                @endif
            </div>

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
{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
     CANCEL REGISTRATION MODAL
â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
<div x-show="showLeaveModal" 
     class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-cloak>
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-8 text-center"
         @click.away="showLeaveModal = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        
        <div class="mx-auto w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mb-6">
            <i class="bi bi-exclamation-octagon text-3xl text-red-600"></i>
        </div>
        
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Cancel Registration?</h3>
        <p class="text-gray-500 mb-8 leading-relaxed">
            Are you sure you want to leave <span class="font-bold text-gray-900" x-text="leavingEventTitle"></span>? Your spot will be released to other volunteers.
        </p>
        
        <div class="flex flex-col gap-3">
            <form :action="`/events/${leavingEventId}/leave`" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 text-white font-bold py-4 rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">
                    Yes, Cancel My Spot
                </button>
            </form>
            <button @click="showLeaveModal = false" class="w-full bg-gray-50 text-gray-600 font-bold py-4 rounded-2xl hover:bg-gray-100 transition-all">
                Nevermind, Stay Registered
            </button>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>
{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
     QR TICKET MODAL
     Opened by openTicketModal(eventId); â€” populated via fetch to /events/{id}/ticket
â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
<div id="qrTicketModal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden px-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
      <div>
        <h3 class="font-bold text-gray-900 text-lg" id="ticketEventName">Loadingâ€¦</h3>
        <p class="text-xs text-gray-500" id="ticketEventDate"></p>
      </div>
      <button onclick="closeTicketModal()" class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-500 transition-colors">
        <i class="bi bi-x-lg text-sm"></i>
      </button>
    </div>

    {{-- QR Code Area --}}
    <div class="flex flex-col items-center px-8 py-6">
      <div id="ticketLoader" class="w-16 h-16 flex items-center justify-center">
        <div class="w-8 h-8 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
      </div>
      <div id="ticketQr" class="hidden w-full flex flex-col items-center gap-3">
        <div id="qrSvgContainer" class="p-4 bg-white border-2 border-gray-100 rounded-xl shadow-inner"></div>
        <div class="text-center">
          <span id="ticketStatusBadge" class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 mb-1"></span>
          <p class="text-[11px] text-gray-400">Show this QR to the event organizer</p>
          <p class="text-[10px] text-gray-300 mt-0.5">Expires: <span id="ticketExpiry"></span></p>
        </div>
      </div>
      <div id="ticketError" class="hidden text-center py-4">
        <i class="bi bi-exclamation-triangle text-3xl text-red-400 block mb-2"></i>
        <p class="text-sm text-red-600 font-medium" id="ticketErrorMsg">Could not load ticket.</p>
      </div>
    </div>

    {{-- Footer note --}}
    <div class="bg-gray-50 px-6 py-3 text-center text-xs text-gray-400">
      <i class="bi bi-shield-check text-emerald-500 mr-1"></i>
      Secure Â· Signed Â· Time-limited
    </div>
  </div>
</div>

{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
     POST-EVENT NOTIFICATION MODAL
     Shown via Reverb/Echo when the organizer fires VolunteerCompletedEvent
â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
<div id="completionModal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden px-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
    <div class="px-8 py-8 text-center">
      <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="bi bi-trophy-fill text-emerald-600 text-3xl"></i>
      </div>
      <h3 class="text-2xl font-bold text-gray-900 mb-2">Thank you for volunteering!</h3>
      <p class="text-gray-500 text-sm mb-6" id="completionMessage">
        The event has ended. Please take a moment to share your experience.
      </p>
      <div class="flex flex-col gap-3">
        <a id="completionFeedbackUrl" href="#"
           class="inline-flex items-center justify-center gap-2 w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors">
          <i class="bi bi-star-fill"></i>
          Rate this Event
        </a>
        <a id="completionSuggestionsUrl" href="#"
           class="inline-flex items-center justify-center gap-2 w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors">
          <i class="bi bi-lightbulb-fill"></i>
          Share a Suggestion
        </a>
        <button onclick="document.getElementById('completionModal').classList.add('hidden')"
                class="text-sm text-gray-400 hover:text-gray-600 transition-colors py-1">
          Maybe later
        </button>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  'use strict';

  // â”€â”€ QR Ticket Modal â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  window.openTicketModal = async function (eventId) {
    const modal      = document.getElementById('qrTicketModal');
    const loader     = document.getElementById('ticketLoader');
    const qrSection  = document.getElementById('ticketQr');
    const errSection = document.getElementById('ticketError');

    // Reset state
    loader.classList.remove('hidden');
    qrSection.classList.add('hidden');
    errSection.classList.add('hidden');
    modal.classList.remove('hidden');

    try {
      const res  = await fetch(`/events/${eventId}/ticket`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
      });
      const data = await res.json();

      if (data.success) {
        document.getElementById('ticketEventName').textContent   = data.event_name;
        document.getElementById('ticketEventDate').textContent   = data.event_date;
        document.getElementById('ticketStatusBadge').textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
        document.getElementById('ticketExpiry').textContent      = data.expires_at;
        document.getElementById('qrSvgContainer').innerHTML      = data.qr_svg;

        loader.classList.add('hidden');
        qrSection.classList.remove('hidden');
      } else {
        throw new Error(data.message ?? 'Failed to load ticket.');
      }
    } catch (err) {
      loader.classList.add('hidden');
      document.getElementById('ticketErrorMsg').textContent = err.message;
      errSection.classList.remove('hidden');
    }
  };

  window.closeTicketModal = function () {
    document.getElementById('qrTicketModal').classList.add('hidden');
  };

  // Close modals on backdrop click
  document.getElementById('qrTicketModal').addEventListener('click', function (e) {
    if (e.target === this) closeTicketModal();
  });

  // â”€â”€ Reverb / Echo Listener â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  // Listens for VolunteerCompletedEvent fired when the organizer ends an event.
  if (typeof window.Echo !== 'undefined') {
    const userId = {{ auth()->id() }};

    window.Echo.private(`user.${userId}`)
      .listen('.VolunteerCompletedEvent', (payload) => {
        const modal       = document.getElementById('completionModal');
        const msgEl       = document.getElementById('completionMessage');
        const feedbackBtn = document.getElementById('completionFeedbackUrl');
        const suggestBtn  = document.getElementById('completionSuggestionsUrl');

        msgEl.textContent          = payload.message ?? 'The event has ended. Please share your experience!';
        feedbackBtn.href           = payload.feedback_url;
        suggestBtn.href            = payload.suggestions_url;

        modal.classList.remove('hidden');
      });
  }

})();
</script>
@endsection

