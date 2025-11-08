@extends('layouts.sidebar.sidebar')

@section('content')
<div class="main-content p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Events
        </a>
    </div>

    <!-- Event Details Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Event Image -->
        <div class="relative h-64 md:h-80 bg-gradient-to-r from-teal-500 to-teal-600">
            <img 
                src="{{ $event->image ?? '/images/event-placeholder.jpg' }}" 
                alt="{{ $event->title }}"
                class="w-full h-full object-cover"
            >
            <div class="absolute top-4 right-4">
                <span class="bg-white text-teal-600 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $event->category ?? 'Event' }}
                </span>
            </div>
            <div class="absolute bottom-4 left-6">
                <span class="bg-teal-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                    {{ $event->status === 'active' ? 'Active' : 'Completed' }}
                </span>
            </div>
        </div>

        <!-- Event Content -->
        <div class="p-6 md:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Event Title -->
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $event->title }}</h1>
                    
                    <!-- Event Meta -->
                    <div class="flex flex-wrap gap-6 mb-6">
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ $event->date->format('F d, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $event->location }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span>{{ $event->current_volunteers }} / {{ $event->required_volunteers }} volunteers</span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Volunteer Progress</span>
                            <span>{{ number_format(($event->current_volunteers / $event->required_volunteers) * 100, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-teal-600 h-2 rounded-full" style="width: {{ ($event->current_volunteers / $event->required_volunteers) * 100 }}%"></div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">About this Event</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $event->description }}</p>
                    </div>

                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
                            {{ session('info') }}
                        </div>
                    @endif

                  <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-4">
                            @auth
                                @php
                                    // Use the model method instead of direct query
                                    $isRegistered = $event->isRegistered(auth()->id());
                                    $isOrganizer = $event->isOrganizer(auth()->id());
                                @endphp

                                @if($event->status === 'active')
                                    @if($isRegistered)
                                        <!-- Already Registered State -->
                                        <div class="flex flex-wrap gap-4 items-center">
                                            <button class="bg-green-600 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 cursor-not-allowed" disabled>
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Already Registered
                                            </button>
                                            
                                            <!-- Leave Button (using the new route) -->
                                            <form action="{{ route('events.leave', $event) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="border border-red-600 text-red-600 px-6 py-3 rounded-lg hover:bg-red-50 transition font-medium flex items-center gap-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Leave Event
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($event->current_volunteers < $event->required_volunteers && !$isOrganizer)
                                        <!-- Join Button (only show if user is NOT the organizer) -->
                                        <form action="{{ route('events.join', $event) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-teal-600 text-white px-6 py-3 rounded-lg hover:bg-teal-700 transition font-medium flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                                </svg>
                                                Join as Volunteer
                                            </button>
                                        </form>
                                    @elseif($isOrganizer)
                                        <!-- Organizer View -->
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                            <p class="text-blue-800 font-medium flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                You are the organizer of this event
                                            </p>
                                            <p class="text-blue-600 text-sm mt-1">Manage your event from the organizer dashboard</p>
                                        </div>
                                    @else
                                        <!-- Event Full State -->
                                        <button class="bg-gray-400 text-white px-6 py-3 rounded-lg font-medium cursor-not-allowed" disabled>
                                            Event Full
                                        </button>
                                    @endif
                                @else
                                    <!-- Event Completed State -->
                                    <button class="bg-gray-400 text-white px-6 py-3 rounded-lg font-medium cursor-not-allowed" disabled>
                                        Event Completed
                                    </button>
                                @endif
                            @else
                                <!-- Not logged in -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <p class="text-blue-800 mb-3">Please log in to volunteer for this event</p>
                                    <a href="{{ route('login') }}" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition font-medium">
                                        Login to Volunteer
                                    </a>
                                </div>
                            @endauth
                        </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Organizer Info -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Organizer</h3>
                        <div class="flex items-center gap-3">
                            @if($event->organizer && $event->organizer->avatar)
                                <img src="{{ $event->organizer->avatar }}" alt="{{ $event->organizer->name }}" class="w-12 h-12 rounded-full">
                            @else
                                <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center">
                                    <span class="text-teal-600 font-semibold text-sm">
                                        {{ $event->organizer ? substr($event->organizer->name, 0, 1) : 'O' }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-800">{{ $event->organizer->name ?? 'Community Organizer' }}</p>
                                <p class="text-sm text-gray-600">Event Host</p>
                            </div>
                        </div>
                    </div>

                    <!-- Event Stats -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Event Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date</span>
                                <span class="font-medium">{{ $event->date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Time</span>
                                <span class="font-medium">{{ $event->date->format('g:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Volunteers Needed</span>
                                <span class="font-medium">{{ $event->required_volunteers }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Spots Left</span>
                                <span class="font-medium">{{ $event->required_volunteers - $event->current_volunteers }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status</span>
                                <span class="font-medium capitalize {{ $event->status === 'active' ? 'text-green-600' : 'text-gray-600' }}">
                                    {{ $event->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection