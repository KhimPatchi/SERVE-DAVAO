@extends('layouts.sidebar.sidebar')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <!-- Enhanced Back Navigation -->
    <div class="max-w-7xl mx-auto mb-8">
        <a href="{{ route('events.index') }}" 
           class="group inline-flex items-center gap-3 text-sm font-medium text-gray-600 hover:text-emerald-600 transition-all duration-300 transform hover:-translate-x-1">
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-white shadow-sm border border-gray-200 group-hover:bg-emerald-50 group-hover:border-emerald-200 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </div>
            <span class="hidden sm:inline">Back to Events</span>
        </a>
    </div>

    <!-- Enhanced Event Details Card -->
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden event-card group">
            <!-- Enhanced Hero Section -->
            <div class="relative h-72 md:h-96 bg-cover bg-center" style="background-image: url('{{ $event->image ?? '/assets/img/event-placeholder.jpg' }}');">
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
                        @if($event->current_volunteers > 0)
                        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 feature-card">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                                <div class="w-2 h-6 bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full"></div>
                                Registered Volunteers ({{ $event->current_volunteers }})
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @php
                                    // FIXED: Use 'volunteer' relationship instead of 'user'
                                    $volunteers = $event->volunteers()->with('volunteer')->where('status', 'registered')->get();
                                @endphp
                                
                                @foreach($volunteers as $volunteer)
                                @php
                                    // FIXED: Changed from ->user to ->volunteer
                                    $user = $volunteer->volunteer;
                                    // COMPREHENSIVE AVATAR FIX FOR VOLUNTEERS
                                    $avatarUrl = null;
                                    
                                    // Check Google avatar first
                                    if ($user && $user->google_avatar) {
                                        $avatarUrl = $user->google_avatar;
                                    }
                                    // Then check regular avatar
                                    elseif ($user && $user->avatar) {
                                        if (str_starts_with($user->avatar, 'http')) {
                                            $avatarUrl = $user->avatar;
                                        } elseif (str_starts_with($user->avatar, 'storage/')) {
                                            $avatarUrl = asset($user->avatar);
                                        } else {
                                            $avatarUrl = asset('storage/' . $user->avatar);
                                        }
                                    }
                                    
                                    $hasValidAvatar = $avatarUrl && filter_var($avatarUrl, FILTER_VALIDATE_URL);
                                    $userInitial = $user ? strtoupper(substr($user->name, 0, 1)) : 'V';
                                @endphp
                                <div class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 border border-gray-200 hover:bg-emerald-50 hover:border-emerald-200 transition-all duration-300">
                                    @if($hasValidAvatar)
                                        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" 
                                             class="w-10 h-10 rounded-full border-2 border-emerald-200 object-cover shadow-sm"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center border-2 border-emerald-200 shadow-sm" style="display: none;">
                                            <span class="text-white font-bold text-sm">
                                                {{ $userInitial }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center border-2 border-emerald-200 shadow-sm">
                                            <span class="text-white font-bold text-sm">
                                                {{ $userInitial }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm truncate">{{ $user->name ?? 'Volunteer' }}</p>
                                        <p class="text-gray-600 text-xs truncate">{{ $user->email ?? '' }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            @if($volunteers->isEmpty())
                            <div class="text-center py-8">
                                <i class="bi bi-people text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">No volunteers registered yet</p>
                            </div>
                            @endif
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
                            @if(session('success'))
                                <div class="flex items-center gap-4 p-6 rounded-2xl bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 shadow-sm feature-card">
                                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center">
                                        <i class="bi bi-check-lg text-white text-lg"></i>
                                    </div>
                                    <p class="text-emerald-800 font-semibold text-lg">{{ session('success') }}</p>
                                </div>
                            @endif

                            @if(session('error'))
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

                                @if($event->isActive())
                                    @if($isRegistered)
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
                                            @if($event->canBeLeft(auth()->id()))
                                            <form action="{{ route('events.leave', $event) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="flex items-center gap-3 px-6 py-3 rounded-xl border border-red-300 text-red-700 bg-white hover:bg-red-50 transition-all duration-300 font-semibold shadow-sm hover:shadow-md hover:scale-105">
                                                    <i class="bi bi-person-dash text-red-600"></i>
                                                    Leave Event
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    @elseif($canJoinEvent)
                                        <!-- Enhanced Join Button -->
                                        <form action="{{ route('events.join', $event) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="group w-full flex items-center justify-center gap-4 px-8 py-5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                                <i class="bi bi-person-plus text-xl group-hover:scale-110 transition-transform"></i>
                                                Join as Volunteer
                                            </button>
                                        </form>
                                    @elseif($hasEventStarted || $hasEventEnded)
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
                                            @if($hasEventStarted && !$hasEventEnded)
                                                <p class="text-orange-600 text-sm mt-2">
                                                    Started: {{ $event->date->setTimezone(config('app.timezone'))->format('M d, Y \\a\\t g:i A') }}
                                                </p>
                                            @endif
                                        </div>
                                    @elseif($isOrganizer)
                                        <!-- Enhanced Organizer View -->
                                        <div class="text-center p-8 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 feature-card">
                                            <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center mx-auto mb-4">
                                                <i class="bi bi-person-gear text-white text-2xl"></i>
                                            </div>
                                            <h4 class="text-2xl font-bold text-blue-900 mb-3">You're the Organizer</h4>
                                            <p class="text-blue-700 text-lg">You created this event</p>
                                        </div>
                                    @elseif($event->isFull())
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
                                
                                @if($hasValidAvatar)
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
                                    <p class="text-gray-600 text-sm">Event Host</p>
                                </div>
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
                                @if($event->skills_required)
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
@endsection