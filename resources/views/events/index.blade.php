    @extends('layouts.sidebar.sidebar')

    @section('title', 'Event Discovery - ServeDavao')

    @section('content')
    <div class="min-h-screen bg-gray-50/60 p-6">

        <!-- Professional Page Header -->
        <header class="mb-8">
            <nav class="mb-4 flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ route('dashboard') }}" class="transition-colors hover:text-gray-700">
                    <i class="bi bi-grid mr-1"></i>Dashboard
                </a>
                <i class="bi bi-chevron-right text-xs"></i>
                <span class="text-gray-800 font-medium">Event Discovery</span>
            </nav>

            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="rounded-2xl bg-gradient-to-r from-purple-500 to-blue-600 p-3">
                            <i class="bi bi-calendar-event text-2xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Event Discovery</h1>
                            <p class="mt-2 text-lg text-gray-600">
                                @auth
                                    @if(Auth::user()->isAdmin())
                                        System-wide event monitoring and management
                                    @elseif(Auth::user()->isVerifiedOrganizer())
                                        Manage your organized events and volunteers
                                    @else
                                        Discover and join volunteer opportunities
                                    @endif
                                @else
                                    Discover and join volunteer opportunities
                                @endauth
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- Export Button for Admins -->
                        @auth
                            @if(Auth::user()->isAdmin())
                            <button class="flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50">
                                <i class="bi bi-download"></i>
                                <span class="hidden sm:inline">Export</span>
                            </button>
                            @endif
                        @endauth

                        @auth
                            @if(Auth::user()->isVerifiedOrganizer() || Auth::user()->isAdmin())
                            <a href="{{ route('events.create') }}"
                            class="inline-flex items-center gap-3 rounded-xl bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-3.5 text-base font-semibold text-white transition-all hover:shadow-lg hover:scale-105 shadow-md">
                                <i class="bi bi-plus-circle text-lg"></i>
                                Create Event
                            </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Professional Statistics Dashboard -->
        <section class="mb-8">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <!-- Available Opportunities - IMPROVED -->
                <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Available Opportunities</p>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $upcomingEvents }}</h2>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="bi bi-calendar-check mr-1"></i>
                                <span>{{ $activeEvents }} active • {{ $eventsNext7Days }} this week</span>
                            </div>
                        </div>
                        <button class="rounded-2xl bg-blue-500/10 p-3 ml-4 hover:bg-blue-500/20 transition-colors"
                                title="Refresh opportunities">
                            <i class="bi bi-arrow-clockwise text-xl text-blue-600"></i>
                        </button>
                    </div>
                </div>

                <!-- Hours Logged - IMPROVED -->
                <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Hours Contributed</p>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $hoursLogged }}<span class="text-lg text-gray-500">h</span></h2>    
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="bi bi-clock-history mr-1"></i>
                                <span>Across {{ $myCompletedEvents }} completed events</span>
                            </div>
                        </div>
                        <a href="{{ route('volunteers.my-events') }}"
                        class="rounded-2xl bg-green-500/10 p-3 ml-4 hover:bg-green-500/20 transition-colors"
                        title="View hours log">
                            <i class="bi bi-bar-chart text-xl text-green-600"></i>
                        </a>
                    </div>
                </div>

                <!-- My Participation - IMPROVED -->
                <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">My Participation</p>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $myUpcomingEvents + $myCompletedEvents }}</h2>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="bi bi-person-check mr-1"></i>
                                <span>{{ $myUpcomingEvents }} upcoming • {{ $myCompletedEvents }} completed</span>
                            </div>
                        </div>
                        <a href="{{ route('volunteers.my-events') }}"
                        class="rounded-2xl bg-purple-500/10 p-3 ml-4 hover:bg-purple-500/20 transition-colors"
                        title="View my events">
                            <i class="bi bi-list-ul text-xl text-purple-600"></i>
                        </a>
                    </div>
                </div>

                <!-- Events I'm Hosting - IMPROVED -->
                <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Events Hosting</p>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $myHostedEvents }}</h2>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="bi bi-megaphone mr-1"></i>
                                <span>{{ $myHostedEvents }} total hosted events</span>
                            </div>
                        </div>
                        <a href="{{ route('volunteers.organized-events') }}"
                        class="rounded-2xl bg-orange-500/10 p-3 ml-4 hover:bg-orange-500/20 transition-colors"
                        title="Manage hosted events">
                            <i class="bi bi-gear text-xl text-orange-600"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Enhanced Calendar Overview Widget -->
        <section class="mb-6">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Upcoming Schedule</h3>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <button class="rounded-lg p-2 hover:bg-gray-100 transition-colors" title="Previous month">
                                <i class="bi bi-chevron-left text-gray-600"></i>
                            </button>
                            <span class="text-sm font-medium text-gray-700 min-w-[120px] text-center">{{ now()->format('F Y') }}</span>
                            <button class="rounded-lg p-2 hover:bg-gray-100 transition-colors" title="Next month">
                                <i class="bi bi-chevron-right text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-1 text-center">
                    @php
                        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                        $today = now();
                        $firstDayOfMonth = $today->copy()->firstOfMonth();
                        $lastDayOfMonth = $today->copy()->lastOfMonth();

                        // Get events for this month
                        $monthEvents = $events->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth]);

                        // Get user's registered events with proper date filtering
                        $userRegisteredEvents = auth()->check() ?
                            auth()->user()->volunteeringEvents->filter(function($event) use ($firstDayOfMonth, $lastDayOfMonth) {
                                return $event->date >= $firstDayOfMonth && $event->date <= $lastDayOfMonth;
                            }) :
                            collect();
                    @endphp

                    <!-- Day headers -->
                    @foreach($days as $day)
                        <div class="text-xs font-semibold text-gray-500 py-2">{{ $day }}</div>
                    @endforeach

                    <!-- Empty days before month starts -->
                    @for($i = 0; $i < $firstDayOfMonth->dayOfWeek; $i++)
                        <div class="h-8"></div>
                    @endfor

                    <!-- Month days -->
                    @for($day = 1; $day <= $lastDayOfMonth->day; $day++)
                        @php
                            $currentDate = $today->copy()->setDay($day);
                            $dayEvents = $monthEvents->filter(function($event) use ($currentDate) {
                                return $event->date->format('Y-m-d') === $currentDate->format('Y-m-d');
                            });

                            // Properly categorize user events
                            $userDayEvents = $userRegisteredEvents->filter(function($event) use ($currentDate) {
                                return $event->date->format('Y-m-d') === $currentDate->format('Y-m-d');
                            });

                            $isToday = $currentDate->isToday();
                            $hasEvents = $dayEvents->count() > 0;
                            $hasUserEvents = $userDayEvents->count() > 0;
                            $isPastDate = $currentDate->lt(now()->startOfDay());
                            $isFutureDate = $currentDate->gt(now()->endOfDay());

                            // Better event categorization
                            $isUserAttending = $hasUserEvents;
                            $isUserAttendingUpcoming = $hasUserEvents && $isFutureDate;
                            $isUserAttendedPast = $hasUserEvents && $isPastDate;
                            $isUserHosting = auth()->check() ? $dayEvents->where('organizer_id', auth()->id())->count() > 0 : false;
                            $hasAvailableEvents = $hasEvents && !$hasUserEvents && !$isUserHosting;
                        @endphp
                        <div class="relative h-8 flex items-center justify-center text-sm
                                    {{ $isToday ? 'bg-blue-500 text-white rounded-full font-bold ring-2 ring-blue-200' :
                                    ($isUserAttendingUpcoming ? 'bg-green-50 rounded-lg border border-green-200' :
                                    ($isUserAttendedPast ? 'bg-blue-50 rounded-lg border border-blue-200' :
                                    'text-gray-700')) }}">
                            <span class="{{ $isToday ? 'text-white' : '' }}">{{ $day }}</span>

                            <!-- Event Indicators -->
                            @if($hasEvents || $hasUserEvents)
                                <div class="absolute -top-1 -right-1 flex gap-0.5">
                                    @if($isUserHosting)
                                        <div class="w-1.5 h-1.5 bg-orange-500 rounded-full" title="You're hosting"></div>
                                    @elseif($isUserAttendingUpcoming)
                                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full" title="You're attending"></div>
                                    @elseif($isUserAttendedPast)
                                        <div class="w-1.5 h-1.5 bg-blue-500 rounded-full" title="You attended"></div>
                                    @elseif($hasAvailableEvents)
                                        <div class="w-1.5 h-1.5 bg-purple-400 rounded-full" title="Available events"></div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>

                <!-- ENHANCED Calendar Legend -->
                <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Event Legend</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs text-gray-600">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full ring-2 ring-green-200"></div>
                            <span class="font-medium">You're attending</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-blue-500 rounded-full ring-2 ring-blue-200"></div>
                            <span class="font-medium">You attended</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-orange-500 rounded-full ring-2 ring-orange-200"></div>
                            <span class="font-medium">You're hosting</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-purple-400 rounded-full ring-2 ring-purple-200"></div>
                            <span class="font-medium">Available events</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Advanced Filter & Search System -->
        <section class="mb-6">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900">
                            @auth
                                @if(Auth::user()->isAdmin())
                                    Event Registry
                                @elseif(Auth::user()->isVerifiedOrganizer())
                                    My Organized Events
                                @else
                                    Available Events
                                @endif
                            @else
                                Available Events
                            @endauth
                        </h2>
                        <p class="text-gray-600">
                            @auth
                                @if(Auth::user()->isAdmin())
                                    Comprehensive view of all system events
                                @elseif(Auth::user()->isVerifiedOrganizer())
                                    Manage and monitor your event portfolio
                                @else
                                    Browse and join volunteer opportunities
                                @endif
                            @else
                                Browse and join volunteer opportunities
                            @endauth
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Enhanced Search -->
                        <div class="relative">
                            <input type="text"
                                placeholder="Search events, locations, skills..."
                                class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 w-64 text-sm bg-white transition-all">
                            <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <!-- Enhanced Filters -->
                        <div class="flex gap-2">
                            <select class="appearance-none pl-4 pr-10 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition-all cursor-pointer">
                                <option>All Dates</option>
                                <option>Today</option>
                                <option>Next 7 Days</option>
                                <option>This Month</option>
                                <option>Registration Closing Soon</option>
                            </select>

                            <select class="appearance-none pl-4 pr-10 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition-all cursor-pointer">
                                <option>All Categories</option>
                                <option>Environmental</option>
                                <option>Education</option>
                                <option>Healthcare</option>
                                <option>Community</option>
                                <option>Emergency</option>
                            </select>

                            @auth
                                @if(!Auth::user()->isAdmin() && !Auth::user()->isVerifiedOrganizer())
                                <select class="appearance-none pl-4 pr-10 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition-all cursor-pointer">
                                    <option>All Locations</option>
                                    <option>Near Me (5km)</option>
                                    <option>Davao City Center</option>
                                    <option>Buhangin District</option>
                                    <option>Toril District</option>
                                </select>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 gap-8">
            <main>
                @if($events->count() > 0)
                    <!-- Professional Events Grid -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($events as $event)
                        @php
                            $progress = $event->required_volunteers > 0
                                ? round(($event->current_volunteers / $event->required_volunteers) * 100)
                                : 0;
                            $spotsLeft = max(0, $event->required_volunteers - $event->current_volunteers);
                            $isFull = $spotsLeft === 0;
                            $isUrgent = $spotsLeft > 0 && $spotsLeft <= 3;
                            $isOrganizer = auth()->check() && $event->organizer_id == auth()->id();

                            // FIXED: Use direct relationship check instead of isRegistered()
                            $isActuallyRegistered = auth()->check() ?
                                !is_null(auth()->user()->volunteerRegistrations
                                    ->where('event_id', $event->id)
                                    ->where('status', 'registered')
                                    ->first()) : false;

                            // FIXED: Date calculations with proper timezone
                            $isToday = $event->date->isToday();
                            $isTomorrow = $event->date->isTomorrow();
                            $isThisWeek = $event->date->between(now(), now()->addDays(7));
                            $registrationClosing = $event->date->diffInHours(now()) <= 24;

                            // Status badges conditions - REDUCED CLUTTER
                            $showTodayBadge = $isToday;
                            $showTomorrowBadge = $isTomorrow && !$isToday;
                            $showRegistrationClosingBadge = $registrationClosing && !$isFull && $event->current_status === 'active';
                            $showOrganizerBadge = $isOrganizer;
                        @endphp

                        <article class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 transition-all duration-500 hover:shadow-xl hover:translate-y-[-2px] {{ $isToday ? 'ring-2 ring-blue-200 bg-blue-50/30' : '' }}">
                            <!-- Date Status Badges - Simplified -->
                            <div class="absolute top-4 left-4 flex flex-col gap-2 transition-all duration-300 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0">
                                @if($showTodayBadge)
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 ring-1 ring-blue-200 transition-all duration-300">
                                    <i class="bi bi-star-fill mr-1"></i>
                                    Today
                                </span>
                                @endif

                                @if($showTomorrowBadge)
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-green-100 text-green-800 ring-1 ring-green-200 transition-all duration-300 delay-75">
                                    <i class="bi bi-sun mr-1"></i>
                                    Tomorrow
                                </span>
                                @endif

                                @if($showRegistrationClosingBadge)
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-orange-100 text-orange-800 ring-1 ring-orange-200 transition-all duration-300 delay-150">
                                    <i class="bi bi-exclamation-triangle mr-1"></i>
                                    Registration Closing
                                </span>
                                @endif
                            </div>

                            <!-- Role-based Status Badges - Simplified -->
                            <div class="absolute top-4 right-4 flex flex-col gap-2 transition-all duration-300 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0">
                                @if($showOrganizerBadge)
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 ring-1 ring-purple-200 transition-all duration-300">
                                    <i class="bi bi-star-fill mr-1"></i>
                                    Your Event
                                </span>
                                @endif

                                @php
                                    // USE THE FIXED AUTOMATIC STATUS
                                    $currentStatus = $event->current_status;

                                    $statusConfig = [
                                        'active' => ['class' => 'bg-green-100 text-green-800 ring-1 ring-green-200', 'icon' => 'bi-check-circle', 'text' => 'Active'],
                                        'completed' => ['class' => 'bg-blue-100 text-blue-800 ring-1 ring-blue-200', 'icon' => 'bi-check-circle-fill', 'text' => 'Completed'],
                                        'pending' => ['class' => 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-200', 'icon' => 'bi-clock', 'text' => 'Pending'],
                                        'cancelled' => ['class' => 'bg-red-100 text-red-800 ring-1 ring-red-200', 'icon' => 'bi-x-circle', 'text' => 'Cancelled'],
                                        'rejected' => ['class' => 'bg-gray-100 text-gray-800 ring-1 ring-gray-200', 'icon' => 'bi-x-circle-fill', 'text' => 'Rejected'],
                                    ];
                                    $config = $statusConfig[$currentStatus] ?? $statusConfig['pending'];
                                @endphp

                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $config['class'] }} transition-all duration-300 delay-75">
                                    <i class="bi {{ $config['icon'] }} mr-1"></i>
                                    {{ $config['text'] }}
                                </span>
                            </div>

                            <!-- Event Header -->
                            <div class="mb-4 mt-8 transition-all duration-300 group-hover:mt-12">
                                <h3 class="mb-3 line-clamp-2 text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors leading-tight">
                                    {{ $event->title }}
                                </h3>
                                <p class="line-clamp-2 text-gray-600 text-sm leading-relaxed group-hover:text-gray-700 transition-colors">
                                    {{ Str::limit($event->description, 120) }}
                                </p>
                            </div>

                            <!-- Event Metadata - Simplified -->
                            <div class="mb-4 space-y-3">
                                <div class="flex items-center text-sm text-gray-600 group-hover:text-gray-700 transition-colors">
                                    <i class="bi bi-geo-alt mr-3 text-base text-purple-500 group-hover:scale-110 transition-transform"></i>
                                    <span class="line-clamp-1 font-medium">{{ $event->location }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 group-hover:text-gray-700 transition-colors">
                                    <i class="bi bi-calendar-event mr-3 text-base text-purple-500 group-hover:scale-110 transition-transform"></i>   
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $event->date->format('M j, Y') }}</span>
                                        <span class="text-xs text-gray-500 group-hover:text-gray-600 transition-colors">
                                            {{ $event->date->format('g:i A') }} • 
                                            <span class="{{ $isToday ? 'text-blue-600 font-semibold' : 'text-gray-500 group-hover:text-gray-600' }}">
                                                {{ $event->time_until_event }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-sm text-gray-600 group-hover:text-gray-700 transition-colors">
                                        <i class="bi bi-person-check mr-3 text-base text-purple-500 group-hover:scale-110 transition-transform"></i> 
                                        <span class="font-medium">{{ $event->organizer->name ?? 'System Organizer' }}</span>
                                    </div>
                                    @if($event->skills_required)
                                    <div class="flex items-center text-xs text-gray-500 bg-gray-50 rounded-lg px-2 py-1 group-hover:bg-gray-100 group-hover:text-gray-600 transition-all">
                                        <i class="bi bi-tags mr-1"></i>
                                        <span>{{ $event->skills_required }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Capacity Analytics - SIMPLIFIED -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between text-xs text-gray-600 mb-2 group-hover:text-gray-700 transition-colors">
                                    <span>Volunteer Capacity</span>
                                    <span class="font-semibold">{{ $event->current_volunteers }} / {{ $event->required_volunteers }}</span>
                                </div>
                                <div class="h-2 rounded-full bg-gray-200 overflow-hidden group-hover:bg-gray-300 transition-colors">
                                    <div class="h-full rounded-full transition-all duration-1000 ease-out
                                                {{ $progress >= 90 ? 'bg-red-500 group-hover:bg-red-600' :
                                                ($progress >= 75 ? 'bg-orange-500 group-hover:bg-orange-600' :
                                                ($progress >= 50 ? 'bg-yellow-500 group-hover:bg-yellow-600' : 'bg-gradient-to-r from-purple-500 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-600')) }}"
                                        style="width: {{ $progress }}%"></div>
                                </div>
                                <div class="mt-1 flex justify-between text-xs">
                                    <span class="text-gray-500 group-hover:text-gray-600 transition-colors">{{ $progress }}% full</span>
                                    <span class="font-medium {{ $spotsLeft <= 3 ? 'text-red-600 group-hover:text-red-700' : 'text-gray-700 group-hover:text-gray-800' }} transition-colors">
                                        {{ $spotsLeft }} spot{{ $spotsLeft !== 1 ? 's' : '' }} left
                                    </span>
                                </div>
                            </div>

                            <!-- FIXED: Role-based Action Panel -->
                            <div class="flex items-center justify-between border-t border-gray-100 pt-4 group-hover:border-gray-200 transition-colors">
                                <div class="flex items-center gap-2">
                                    @auth
                                        @if($isOrganizer)
                                        <span class="inline-flex items-center text-xs text-purple-600 font-medium group-hover:text-purple-700 transition-colors">
                                            <i class="bi bi-gear mr-1 group-hover:rotate-90 transition-transform"></i>
                                            Manage Event
                                        </span>
                                        @else
                                            @if($isActuallyRegistered)
                                            <span class="inline-flex items-center text-xs text-green-600 font-medium group-hover:text-green-700 transition-colors">
                                                <i class="bi bi-check-circle mr-1 group-hover:scale-110 transition-transform"></i>
                                                Already Joined
                                            </span>
                                            @else
                                            <span class="inline-flex items-center text-xs text-gray-500 group-hover:text-gray-600 transition-colors">
                                                <i class="bi bi-eye mr-1 group-hover:scale-110 transition-transform"></i>
                                                View Details
                                            </span>
                                            @endif
                                        @endif
                                    @endauth
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('events.show', $event) }}"
                                    class="group/btn inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition-all hover:shadow-lg hover:scale-105
                                            {{ $isOrganizer ? 'bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700' : 'bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700' }}">
                                        <i class="bi {{ $isOrganizer ? 'bi-gear' : 'bi-eye' }} transition-transform group-hover/btn:translate-x-0.5"></i>
                                        {{ $isOrganizer ? 'Manage Event' : 'View Details' }}
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
                                Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }} events
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
                            <div class="mb-6 inline-flex rounded-2xl bg-gradient-to-r from-purple-50 to-blue-50 p-8">
                                <i class="bi bi-calendar-x text-5xl text-purple-500"></i>
                            </div>
                            <h3 class="mb-4 text-2xl font-bold text-gray-900">
                                @auth
                                    @if(Auth::user()->isVerifiedOrganizer())
                                        No Events Created Yet
                                    @else
                                        No Events Available
                                    @endif
                                @else
                                    No Events Available
                                @endauth
                            </h3>
                            <p class="mb-8 text-lg text-gray-600 leading-relaxed">
                                @auth
                                    @if(Auth::user()->isVerifiedOrganizer())
                                        You haven't created any events yet. Start by creating your first event to engage volunteers.
                                    @else
                                        There are currently no events available. Check back later for new opportunities!
                                    @endif
                                @else
                                    There are currently no events available. Check back later for new opportunities!
                                @endauth
                            </p>
                            <div class="space-y-4">
                                @auth
                                    @if(Auth::user()->isVerifiedOrganizer() || Auth::user()->isAdmin())
                                    <a href="{{ route('events.create') }}"
                                    class="inline-flex items-center gap-3 rounded-xl bg-gradient-to-r from-purple-600 to-blue-600 px-8 py-4 text-lg font-semibold text-white transition-all hover:shadow-lg hover:scale-105 shadow-md">
                                        <i class="bi bi-plus-circle"></i>
                                        Create Your First Event
                                    </a>
                                    @endif
                                @endauth
                                <div class="text-sm text-gray-500">
                                    <i class="bi bi-info-circle mr-1"></i>
                                    @auth
                                        @if(Auth::user()->isVerifiedOrganizer())
                                            Events help you organize volunteer activities and manage participants
                                        @else
                                            Events are created by verified organizers in your community
                                        @endif
                                    @else
                                        Events are created by verified organizers in your community
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </main>
        </div>

    </div>

    <!-- Professional JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real-time search functionality
        const searchInput = document.querySelector('input[type="text"]');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = e.target.value.toLowerCase();
                    const eventCards = document.querySelectorAll('article');
                    let visibleCount = 0;

                    eventCards.forEach(card => {
                        const title = card.querySelector('h3').textContent.toLowerCase();
                        const description = card.querySelector('p').textContent.toLowerCase();
                        const location = card.querySelector('[class*="line-clamp-1"]').textContent.toLowerCase();

                        const isVisible = title.includes(searchTerm) ||
                                        description.includes(searchTerm) ||
                                        location.includes(searchTerm);

                        card.style.display = isVisible ? 'block' : 'none';
                        if (isVisible) visibleCount++;
                    });

                    // Update results counter if exists
                    const resultsCounter = document.querySelector('[class*="text-gray-600"]');
                    if (resultsCounter && visibleCount > 0) {
                        resultsCounter.textContent = `Showing ${visibleCount} of ${eventCards.length} events`;
                    }
                }, 300);
            });
        }

        // Calendar navigation
        const prevMonthBtn = document.querySelector('button[title="Previous month"]');
        const nextMonthBtn = document.querySelector('button[title="Next month"]');
        
        if (prevMonthBtn && nextMonthBtn) {
            // Note: This would need backend integration for full functionality
            prevMonthBtn.addEventListener('click', function() {
                alert('Previous month navigation would be implemented with backend integration');
            });
            
            nextMonthBtn.addEventListener('click', function() {
                alert('Next month navigation would be implemented with backend integration');
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

        // Observe event cards for scroll animations
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