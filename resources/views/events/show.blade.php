@extends ('layouts.sidebar.sidebar')

@push ('head')
<link href="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css" rel="stylesheet">
<style>
    #event-map { height: 100%; width: 100%; }

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
@endpush

@section ('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
   <!-- Secure Back Navigation -->
<div class="max-w-7xl mx-auto mb-8">
    @auth
        <!-- For logged-in users: Smart back button -->
        <button onclick="handleBackNavigation()" 
               class="group inline-flex items-center gap-3 text-sm font-medium text-gray-600 hover:text-emerald-600 transition-all duration-300 transform hover:-translate-x-1">
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-white shadow-sm border border-gray-200 group-hover:bg-emerald-50 group-hover:border-emerald-200 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </div>
            <span class="hidden sm:inline">Back to Previous</span>
        </button>
    @else
        <!-- For logged-out users: Always go to home -->
        <a href="{{ route('landing') }}" 
           class="group inline-flex items-center gap-3 text-sm font-medium text-gray-600 hover:text-emerald-600 transition-all duration-300 transform hover:-translate-x-1">
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-white shadow-sm border border-gray-200 group-hover:bg-emerald-50 group-hover:border-emerald-200 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </div>
            <span class="hidden sm:inline">Back to Home</span>
        </a>
    @endauth
</div>

@auth
<script>
function handleBackNavigation() {
    const previousUrl = "{{ session('previous_url', '') }}";
    
    // If we have a stored previous URL and it's not the current page
    if (previousUrl && previousUrl !== "{{ url()->current() }}") {
        window.location.href = previousUrl;
    } else {
        // Use browser history or fallback to events index
        if (document.referrer && document.referrer.includes(window.location.host)) {
            window.history.back();
        } else {
            window.location.href = "{{ route('events.index') }}";
        }
    }
}
</script>
@endauth
    <!-- Enhanced Event Details Card -->
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden event-card group">
            <!-- Enhanced Hero Section -->
            <div class="relative h-72 md:h-96 bg-cover bg-center" style="background-image: url('{{ $event->image ? asset('storage/' . $event->image) : asset('assets/img/event-placeholder.jpg') }}');">
                <div class="absolute inset-0 bg-gradient-to-br from-black/40 to-emerald-900/30"></div>
                
                <!-- Enhanced Status Badges -->
                <div class="absolute top-6 right-6 flex flex-col gap-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/95 backdrop-blur-sm text-emerald-700 text-sm font-semibold shadow-lg border border-white/20">
                        {{ $event->category ?? 'Community Event' }}
                    </span>
                    @php
                        $statusConfig = [
                            'active' => ['class' => 'bg-emerald-500 text-white', 'icon' => 'bi-check-circle'],
                            'completed' => ['class' => 'bg-blue-500 text-white', 'icon' => 'bi-check-circle-fill'],
                            'pending' => ['class' => 'bg-amber-500 text-white', 'icon' => 'bi-clock'],
                            'cancelled' => ['class' => 'bg-red-500 text-white', 'icon' => 'bi-x-circle'],
                            'rejected' => ['class' => 'bg-gray-500 text-white', 'icon' => 'bi-x-circle-fill']
                        ];
                        $config = $statusConfig[$event->current_status] ?? $statusConfig['pending'];
                    @endphp
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $config['class'] }} text-sm font-semibold shadow-lg">
                        <i class="bi {{ $config['icon'] }}"></i>
                        {{ $event->current_status === 'active' ? 'Active' : ($event->current_status === 'completed' ? 'Completed' : ucfirst($event->current_status)) }}
                    </span>

                    @if (auth()->check() && isset($recommendations))
                        @php
                            $match = $recommendations->firstWhere('event.id', $event->id);
                        @endphp
                        @if ($match && $match['match_percentage'] > 0)
                            <!-- Premium Match Badge -->
                            <div class="glass-match-badge px-4 py-2 rounded-full text-white text-sm font-black shadow-lg border-2 border-white/30 flex items-center gap-2">
                                <i class="bi bi-stars"></i>
                                <span>{{ $match['match_percentage'] }}% MATCH</span>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Enhanced Event Title Overlay -->
                <div class="absolute bottom-6 left-6 right-6">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-4 drop-shadow-2xl leading-tight group-hover:text-emerald-100 transition-colors duration-300">
                        {{ $event->title }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-4 text-white/90 text-sm">
                        <div class="flex items-center gap-2 backdrop-blur-sm bg-white/10 rounded-full px-4 py-2 transition-all duration-300 hover:bg-white/20">
                            <i class="bi bi-geo-alt text-emerald-300"></i>
                            <span class="font-medium">{{ $event->location }}</span>
                        </div>
                        <div class="flex items-center gap-2 backdrop-blur-sm bg-white/10 rounded-full px-4 py-2 transition-all duration-300 hover:bg-white/20">
                            <i class="bi bi-calendar-event text-emerald-300"></i>
                            <span class="font-medium">{{ $event->date->setTimezone(config('app.timezone'))->format('M d, Y \\a\\t g:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Content Section -->
            <div class="p-8 md:p-12">
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-12">
                    <!-- Main Content -->
                    <div class="xl:col-span-2 space-y-8">
                        <!-- Enhanced Progress Section -->
                        <div class="bg-gray-50 rounded-2xl p-8 shadow-sm border border-gray-100 feature-card">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                                    <div class="w-2 h-6 bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full"></div>
                                    Volunteer Progress
                                </h3>
                                <span class="text-3xl font-extrabold text-emerald-600">
                                    {{ number_format(($event->current_volunteers / $event->required_volunteers) * 100, 1) }}%
                                </span>
                            </div>
                            <div class="space-y-4">
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 transition-all duration-1000 ease-out" 
                                         style="width: {{ ($event->current_volunteers / $event->required_volunteers) * 100 }}%">
                                    </div>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span class="flex items-center gap-2">
                                        <i class="bi bi-people-fill text-emerald-500"></i>
                                        {{ $event->current_volunteers }} volunteers registered
                                    </span>
                                    <span class="font-semibold text-emerald-700">{{ $event->required_volunteers - $event->current_volunteers }} spots remaining</span>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Registered Volunteers Section -->
                        @if ($event->current_volunteers > 0)
                        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 feature-card">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                                <div class="w-2 h-6 bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full"></div>
                                Registered Volunteers ({{ $event->current_volunteers }})
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @php
                                    $volunteersList = $event->volunteers()
                                        ->with('volunteer')
                                        ->whereIn('status', ['registered', 'attended', 'no-show'])
                                        ->get();

                                    $statusBadge = [
                                        'attended'   => ['bg-emerald-100 text-emerald-700', 'bi-check2-circle', 'Attended'],
                                        'no-show'    => ['bg-red-100 text-red-700',         'bi-x-circle',      'No-Show'],
                                    ];
                                @endphp

                                @foreach ($volunteersList as $vRecord)
                                @php
                                    $vUser = $vRecord->volunteer;
                                    $vAvatarUrl = null;
                                    if ($vUser && $vUser->google_avatar) $vAvatarUrl = $vUser->google_avatar;
                                    elseif ($vUser && $vUser->avatar) {
                                        $vAvatarUrl = str_starts_with($vUser->avatar, 'http') ? $vUser->avatar : asset('storage/' . $vUser->avatar);
                                    }
                                    $vInitial = $vUser ? strtoupper(substr($vUser->name, 0, 1)) : 'V';
                                @endphp
                                <div class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 border border-gray-200 hover:bg-emerald-50 hover:border-emerald-200 transition-all duration-300">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center border-2 border-emerald-200 shadow-sm overflow-hidden flex-shrink-0">
                                        @if ($vAvatarUrl)
                                            <img src="{{ $vAvatarUrl }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-white font-bold text-sm">{{ $vInitial }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm truncate">{{ $vUser->name ?? 'Volunteer' }}</p>
                                        @if (isset($statusBadge[$vRecord->status]))
                                        @php [$vBClass, $vBIcon, $vBText] = $statusBadge[$vRecord->status]; @endphp
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $vBClass }} mt-0.5">
                                            <i class="bi {{ $vBIcon }}"></i> {{ $vBText }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif



                        <!-- Enhanced Description -->
                        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 feature-card">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                                <div class="w-2 h-6 bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full"></div>
                                About this Event
                            </h3>
                            <p class="text-gray-700 leading-relaxed text-lg">{{ $event->description }}</p>
                        </div>

                        <!-- Enhanced Flash Messages -->
                        <div class="space-y-4">
                            @if (session('success'))
                                <div class="flex items-center gap-4 p-6 rounded-2xl bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 shadow-sm feature-card">
                                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center">
                                        <i class="bi bi-check-lg text-white text-lg"></i>
                                    </div>
                                    <p class="text-emerald-800 font-semibold text-lg">{{ session('success') }}</p>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="flex items-center gap-4 p-6 rounded-2xl bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 shadow-sm feature-card">
                                    <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center">
                                        <i class="bi bi-x-lg text-white text-lg"></i>
                                    </div>
                                    <p class="text-red-800 font-semibold text-lg">{{ session('error') }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Enhanced Action Buttons -->
                        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 feature-card">
                            @auth
                                @php
                                    // FIXED: Use consistent model methods instead of custom logic
                                    $isRegistered = $event->isRegistered(auth()->id());
                                    $isOrganizer = $event->isOrganizer(auth()->id());
                                    $hasEventStarted = $event->hasStarted();
                                    $hasEventEnded = $event->hasEnded();
                                    $canJoinEvent = $event->canBeJoined(auth()->id());
                                @endphp

                                @if ($event->isActive())
                                    @if ($isRegistered)
                                        <!-- Enhanced Registered State -->
                                        <div class="flex flex-col sm:flex-row gap-6 items-center justify-between p-8 rounded-2xl bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 feature-card">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-full bg-emerald-500 flex items-center justify-center">
                                                    <i class="bi bi-check-lg text-white text-xl"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-emerald-900 text-lg">You're registered!</p>
                                                    <p class="text-emerald-700">Thank you for volunteering</p>
                                                </div>
                                            </div>
                                            <div class="flex flex-col sm:flex-row gap-3">
                                                @if ($isRegistered || $isOrganizer)
                                                    <a href="{{ route('messages.start-event', $event) }}" 
                                                       class="flex items-center gap-3 px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-all duration-300 font-semibold shadow-sm hover:shadow-md hover:scale-105">
                                                        <i class="bi bi-chat-dots-fill"></i>
                                                        Event Group Chat
                                                    </a>
                                                @endif
                                                @if ($event->canBeLeft(auth()->id()))
                                                <form id="leave-form" action="{{ route('events.leave', $event) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                            onclick="openConfirmModal('leave')"
                                                            class="flex items-center gap-3 px-6 py-3 rounded-xl border border-red-300 text-red-700 bg-white hover:bg-red-50 transition-all duration-300 font-semibold shadow-sm hover:shadow-md hover:scale-105">
                                                        <i class="bi bi-person-dash text-red-600"></i>
                                                        Leave Event
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif ($canJoinEvent)
                                        <!-- Enhanced Join Button -->
                                        <form id="join-form" action="{{ route('events.join', $event) }}" method="POST">
                                            @csrf
                                            <button type="button"
                                                    onclick="openConfirmModal('join')"
                                                    class="group w-full flex items-center justify-center gap-4 px-8 py-5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                                <i class="bi bi-person-plus text-xl group-hover:scale-110 transition-transform"></i>
                                                Join as Volunteer
                                            </button>
                                        </form>
                                    @elseif ($hasEventStarted || $hasEventEnded)
                                        <!-- Enhanced Event Status -->
                                        <div class="text-center p-8 rounded-2xl bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 feature-card">
                                            <div class="w-16 h-16 rounded-full bg-orange-500 flex items-center justify-center mx-auto mb-4">
                                                <i class="bi bi-clock-history text-white text-2xl"></i>
                                            </div>
                                            <h4 class="text-2xl font-bold text-orange-900 mb-3">
                                                {{ $hasEventEnded ? 'Event Completed' : 'Event Ongoing' }}
                                            </h4>
                                            <p class="text-orange-700 text-lg">
                                                {{ $hasEventEnded ? 'This event has already taken place' : 'This event is currently in progress' }}
                                            </p>
                                            @if ($hasEventStarted && !$hasEventEnded)
                                                <p class="text-orange-600 text-sm mt-2">
                                                    Started: {{ $event->date->setTimezone(config('app.timezone'))->format('M d, Y \\a\\t g:i A') }}
                                                </p>
                                            @endif
                                        </div>
                                    @elseif ($isOrganizer)
                                        <!-- Enhanced Organizer View -->
                                        <div class="text-center p-8 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 feature-card">
                                            <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center mx-auto mb-4">
                                                <i class="bi bi-person-gear text-white text-2xl"></i>
                                            </div>
                                            <h4 class="text-2xl font-bold text-blue-900 mb-3">You're the Organizer</h4>
                                            <p class="text-blue-700 text-lg">You created this event</p>
                                        </div>
                                    @elseif ($event->isFull())
                                        <!-- Enhanced Event Full State -->
                                        <div class="text-center p-8 rounded-2xl bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 feature-card">
                                            <div class="w-16 h-16 rounded-full bg-gray-400 flex items-center justify-center mx-auto mb-4">
                                                <i class="bi bi-people text-white text-2xl"></i>
                                            </div>
                                            <h4 class="text-2xl font-bold text-gray-700 mb-3">Event Full</h4>
                                            <p class="text-gray-600">All volunteer spots have been filled</p>
                                        </div>
                                    @else
                                        <!-- Fallback State -->
                                        <div class="text-center p-8 rounded-2xl bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 feature-card">
                                            <div class="w-16 h-16 rounded-full bg-gray-400 flex items-center justify-center mx-auto mb-4">
                                                <i class="bi bi-info-circle text-white text-2xl"></i>
                                            </div>
                                            <h4 class="text-2xl font-bold text-gray-700 mb-3">Cannot Join Event</h4>
                                            <p class="text-gray-600">This event is not available for joining at this time</p>
                                        </div>
                                    @endif
                                @else
                                    <!-- Enhanced Completed State -->
                                    <div class="text-center p-8 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 feature-card">
                                        <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center mx-auto mb-4">
                                            <i class="bi bi-check-circle text-white text-2xl"></i>
                                        </div>
                                        <h4 class="text-2xl font-bold text-blue-900 mb-3">Event Completed</h4>
                                        <p class="text-blue-700 text-lg">This event has already taken place</p>
                                    </div>
                                @endif
                            @else
                                <!-- Enhanced Not Logged In -->
                                <div class="text-center p-8 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 feature-card">
                                    <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center mx-auto mb-4">
                                        <i class="bi bi-person-up text-white text-2xl"></i>
                                    </div>
                                    <h4 class="text-2xl font-bold text-blue-900 mb-4">Join this Event</h4>
                                    <p class="text-blue-700 text-lg mb-6">Sign in to volunteer for this community event</p>
                                    <a href="{{ route('login') }}" 
                                       class="inline-flex items-center gap-4 px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold hover:from-blue-700 hover:to-indigo-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                                        <i class="bi bi-box-arrow-in-right"></i>
                                        Sign In to Volunteer
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>

                    <!-- Enhanced Sidebar - FIXED AVATAR DISPLAY -->
                    <div class="xl:col-span-1 space-y-6">
                        <!-- Enhanced Organizer Card -->
                        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 feature-card">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                                <i class="bi bi-person-badge text-emerald-500"></i>
                                Event Organizer
                            </h3>
                            <div class="flex items-center gap-4 p-6 rounded-xl bg-gray-50 border border-gray-200">
                                @php
                                    $organizer = $event->organizer;
                                    // COMPREHENSIVE AVATAR FIX
                                    $avatarUrl = null;
                                    // Check Google avatar first
                                    if ($organizer && $organizer->google_avatar) {
                                        $avatarUrl = $organizer->google_avatar;
                                    }
                                    // Then check regular avatar
                                    elseif ($organizer && $organizer->avatar) {
                                        if (str_starts_with($organizer->avatar, 'http')) {
                                            $avatarUrl = $organizer->avatar;
                                        } elseif (str_starts_with($organizer->avatar, 'storage/')) {
                                            $avatarUrl = asset($organizer->avatar);
                                        } else {
                                            $avatarUrl = asset('storage/' . $organizer->avatar);
                                        }
                                    }
                                    $hasValidAvatar = $avatarUrl && filter_var($avatarUrl, FILTER_VALIDATE_URL);
                                @endphp
                                
                                @if ($hasValidAvatar)
                                    <img src="{{ $avatarUrl }}" alt="{{ $organizer->name }}" 
                                         class="w-16 h-16 rounded-full border-2 border-emerald-200 shadow-sm object-cover"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center border-2 border-emerald-200 shadow-sm" style="display: none;">
                                        <span class="text-white font-bold text-xl">
                                            {{ $organizer ? strtoupper(substr($organizer->name, 0, 1)) : 'O' }}
                                        </span>
                                    </div>
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center border-2 border-emerald-200 shadow-sm">
                                        <span class="text-white font-bold text-xl">
                                            {{ $organizer ? strtoupper(substr($organizer->name, 0, 1)) : 'O' }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 text-lg truncate">{{ $organizer->name ?? 'Community Organizer' }}</p>      
                                    <p class="text-gray-600 text-sm mb-3">Event Host</p>
                                    @auth
                                        @if (auth()->id() !== $organizer->id)
                                            <a href="{{ route('messages.start-direct', $organizer->id) }}" 
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition-all shadow-sm hover:shadow-md">
                                                <i class="bi bi-chat-fill"></i>
                                                Message Organizer
                                            </a>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <!-- Event Map Card -->
                        <div id="map-card" class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 feature-card {{ $event->latitude && $event->longitude ? '' : 'hidden' }}">
                            <div class="h-48 w-full p-1">
                                <div id="event-map" class="rounded-xl overflow-hidden"></div>
                            </div>
                            <div class="px-6 py-3 bg-gray-50/50 flex items-center justify-between border-t border-gray-100">
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-1">
                                    <i class="bi bi-geo-alt-fill text-emerald-500"></i>
                                    Event Location
                                </span>
                                @if ($event->latitude && $event->longitude)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $event->latitude }},{{ $event->longitude }}" 
                                   target="_blank" 
                                   class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 transition-colors uppercase tracking-widest flex items-center gap-1">
                                    Open in Maps <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                                @endif
                            </div>
                        </div>

                        <!-- Enhanced Event Details Card -->
                        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 feature-card">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                                <i class="bi bi-info-circle text-emerald-500"></i>
                                Event Details
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 border border-gray-200">
                                    <span class="text-gray-600 font-medium flex items-center gap-2">
                                        <i class="bi bi-calendar text-emerald-500"></i>
                                        Date & Time
                                    </span>
                                    <span class="text-gray-900 font-semibold text-right">
                                        {{ $event->date->setTimezone(config('app.timezone'))->format('M d, Y') }}<br>
                                        <span class="text-sm text-gray-600">{{ $event->date->setTimezone(config('app.timezone'))->format('g:i A') }}</span>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 border border-gray-200">
                                    <span class="text-gray-600 font-medium flex items-center gap-2">
                                        <i class="bi bi-geo-alt text-emerald-500"></i>
                                        Location
                                    </span>
                                    <span class="text-gray-900 font-semibold text-right">{{ $event->location }}</span>
                                </div>
                                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 border border-gray-200">
                                    <span class="text-gray-600 font-medium flex items-center gap-2">
                                        <i class="bi bi-people text-emerald-500"></i>
                                        Volunteers
                                    </span>
                                    <span class="text-gray-900 font-semibold">{{ $event->current_volunteers }}/{{ $event->required_volunteers }}</span>
                                </div>
                                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 border border-gray-200">
                                    <span class="text-gray-600 font-medium flex items-center gap-2">
                                        <i class="bi bi-person-plus text-emerald-500"></i>
                                        Spots Left
                                    </span>
                                    <span class="text-emerald-600 font-bold text-lg">{{ $event->required_volunteers - $event->current_volunteers }}</span>
                                </div>
                                @if ($event->skills_required)
                                <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                                    <span class="text-gray-600 font-medium block mb-2 flex items-center gap-2">
                                        <i class="bi bi-tools text-emerald-500"></i>
                                        Skills Required
                                    </span>
                                    <span class="text-gray-900 font-semibold">{{ $event->skills_required }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .event-card {
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .event-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .feature-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .feature-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    // Add hover effects to match landing page
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to all interactive elements
        const hoverElements = document.querySelectorAll('.feature-card, .event-card, a, button');
        hoverElements.forEach(el => {
            el.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            el.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>

<!-- â”€â”€â”€â”€â”€ Join / Leave Confirmation Modal â”€â”€â”€â”€â”€ -->
<div id="confirm-modal"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
     onclick="if(event.target===this) closeConfirmModal()">

    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    <!-- Card -->
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 transform transition-all duration-300 scale-95 opacity-0" id="confirm-card">

        <!-- Icon -->
        <div id="modal-icon-wrap" class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i id="modal-icon" class="text-3xl"></i>
        </div>

        <!-- Title -->
        <h3 id="modal-title" class="text-2xl font-black text-gray-900 text-center mb-2"></h3>

        <!-- Body -->
        <p id="modal-body" class="text-gray-500 text-center text-sm leading-relaxed mb-6"></p>

        <!-- Event name chip -->
        <div class="bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 mb-6 flex items-center gap-3">
            <i class="bi bi-calendar-event text-gray-400"></i>
            <span class="text-sm font-semibold text-gray-700 line-clamp-1">{{ $event->title }}</span>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3">
            <button onclick="closeConfirmModal()"
                    class="flex-1 h-12 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button id="modal-confirm-btn"
                    onclick="submitConfirmedForm()"
                    class="flex-1 h-12 rounded-xl text-white font-bold transition-all hover:scale-105 shadow-sm">
            </button>
        </div>
    </div>
</div>

<script>
let _pendingAction = null;

const MODAL_CONFIG = {
    join: {
        icon: 'bi-person-plus',
        iconBg: 'bg-emerald-100',
        iconColor: 'text-emerald-600',
        title: 'Join this Event?',
        body: 'You will be registered as a volunteer. You can leave before the event starts if your plans change.',
        btnText: 'Yes, Join Event',
        btnClass: 'bg-emerald-600 hover:bg-emerald-700',
    },
    leave: {
        icon: 'bi-person-dash',
        iconBg: 'bg-red-100',
        iconColor: 'text-red-600',
        title: 'Leave this Event?',
        body: 'You will be removed from the volunteer list. You may be able to rejoin if spots are still available.',
        btnText: 'Yes, Leave Event',
        btnClass: 'bg-red-500 hover:bg-red-600',
    },
};

function openConfirmModal(action) {
    const cfg = MODAL_CONFIG[action];
    _pendingAction = action;

    const iconWrap = document.getElementById('modal-icon-wrap');
    const icon     = document.getElementById('modal-icon');
    iconWrap.className = `w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5 ${cfg.iconBg}`;
    icon.className     = `bi ${cfg.icon} text-3xl ${cfg.iconColor}`;

    document.getElementById('modal-title').textContent = cfg.title;
    document.getElementById('modal-body').textContent  = cfg.body;

    const btn = document.getElementById('modal-confirm-btn');
    btn.textContent = cfg.btnText;
    btn.className   = `flex-1 h-12 rounded-xl text-white font-bold transition-all hover:scale-105 shadow-sm ${cfg.btnClass}`;

    const modal = document.getElementById('confirm-modal');
    const card  = document.getElementById('confirm-card');
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        card.classList.remove('scale-95', 'opacity-0');
        card.classList.add('scale-100', 'opacity-100');
    });
}

function closeConfirmModal() {
    const card  = document.getElementById('confirm-card');
    const modal = document.getElementById('confirm-modal');
    card.classList.remove('scale-100', 'opacity-100');
    card.classList.add('scale-95', 'opacity-0');
    setTimeout(() => { modal.classList.add('hidden'); _pendingAction = null; }, 200);
}

function submitConfirmedForm() {
    if (_pendingAction === 'join')  document.getElementById('join-form').submit();
    if (_pendingAction === 'leave') document.getElementById('leave-form').submit();
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeConfirmModal(); });
</script>

@push ('scripts')
<script src="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = '{{ config("services.mapbox.token") }}';
        const lat = {{ $event->latitude ?? 'null' }};
        const lng = {{ $event->longitude ?? 'null' }};

        if (token && lat && lng) {
            mapboxgl.accessToken = token;
            
            // Check if coordinates are valid Davao region (roughly) to prevent zoomed out views
            const davaoBounds = [125.0, 6.5, 126.2, 7.6];
            
            const map = new mapboxgl.Map({
                container: 'event-map',
                style: 'mapbox://styles/mapbox/light-v11',
                center: [lng, lat],
                zoom: 14,
                attributionControl: false
            });

            // Custom Emerald Marker
            new mapboxgl.Marker({ color: '#10b981' })
                .setLngLat([lng, lat])
                .addTo(map);

            // Add subtle zoom controls
            map.addControl(new mapboxgl.NavigationControl({ showCompass: false }), 'top-right');
        } else {
            const mapCard = document.getElementById('map-card');
            if (mapCard) mapCard.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection

