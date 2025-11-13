@extends('layouts.sidebar.sidebar')
@section('content')

<div class="p-6 bg-gray-50 min-h-screen" id="dashboard">
  
  <!-- Enhanced Dynamic Header -->
  <div class="flex justify-between items-center mb-8">
    <div>
      @if(auth()->user()->isVerifiedOrganizer())
      <h1 class="text-3xl font-bold text-gray-800">Organizer Dashboard</h1>
      <p class="text-gray-600 mt-2">Manage your events and track volunteer engagement across Davao.</p>
      @else
      <h1 class="text-3xl font-bold text-gray-800">Volunteer Dashboard</h1>
      <p class="text-gray-600 mt-2">Your journey in serving the Davao community starts here.</p>
      @endif
    </div>
    <div class="text-right">
      @if(auth()->user()->isVerifiedOrganizer())
        <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-semibold inline-flex items-center gap-1">
          <i class="bi bi-patch-check"></i>
          Verified Organizer
        </span>
      @elseif(auth()->user()->hasPendingVerification())
        <span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-sm font-semibold inline-flex items-center gap-1">
          <i class="bi bi-clock"></i>
          Verification Pending
        </span>
      @else
        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold inline-flex items-center gap-1">
          <i class="bi bi-heart-fill"></i>
          Active Volunteer
        </span>
      @endif
    </div>
  </div>

  <!-- Enhanced Stats Cards - Improved Visual Hierarchy -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Volunteers/Registrations -->
    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group border border-gray-100">
      <div class="flex items-center gap-4">
        <div class="bg-emerald-100 p-3 rounded-xl group-hover:scale-110 transition-transform duration-300">
          <i class="bi bi-people-fill text-emerald-600 text-2xl"></i>
        </div>
        <div class="flex-1">
          <p class="text-gray-500 text-sm font-medium mb-1">
            @if(auth()->user()->isVerifiedOrganizer())
              Total Volunteers
            @else
              Events Registered
            @endif
          </p>
          <h2 class="text-2xl font-bold text-gray-800">{{ $totalVolunteers }}</h2>
          <div class="w-full bg-gray-100 rounded-full h-2 mt-3">
            <div class="bg-emerald-500 h-2 rounded-full transition-all duration-1000 ease-out" 
                 style="width: {{ min(($totalVolunteers / max($totalVolunteers, 1)) * 100, 100) }}%">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Upcoming Events -->
    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group border border-gray-100">
      <div class="flex items-center gap-4">
        <div class="bg-blue-100 p-3 rounded-xl group-hover:scale-110 transition-transform duration-300">
          <i class="bi bi-calendar-event text-blue-600 text-2xl"></i>
        </div>
        <div class="flex-1">
          <p class="text-gray-500 text-sm font-medium mb-1">
            @if(auth()->user()->isVerifiedOrganizer())
              Active Events
            @else
              Upcoming Events
            @endif
          </p>
          <h2 class="text-2xl font-bold text-gray-800">{{ $upcomingEvents }}</h2>
          <div class="w-full bg-gray-100 rounded-full h-2 mt-3">
            <div class="bg-blue-500 h-2 rounded-full transition-all duration-1000 ease-out" 
                 style="width: {{ min(($upcomingEvents / max($upcomingEvents, 1)) * 100, 100) }}%">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Volunteer Hours - Enhanced with progress ring concept -->
    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group border border-gray-100">
      <div class="flex items-center gap-4">
        <div class="bg-amber-100 p-3 rounded-xl group-hover:scale-110 transition-transform duration-300">
          <i class="bi bi-clock-fill text-amber-600 text-2xl"></i>
        </div>
        <div class="flex-1">
          <p class="text-gray-500 text-sm font-medium mb-1">Hours Contributed</p>
          <h2 class="text-2xl font-bold text-gray-800">
            @if($totalHours > 0)
              {{ $totalHours }}
            @else
              -
            @endif
          </h2>
          <div class="w-full bg-gray-100 rounded-full h-2 mt-3">
            <div class="bg-amber-500 h-2 rounded-full transition-all duration-1000 ease-out" 
                 style="width: {{ min(($totalHours / max($totalHours, 1)) * 10, 100) }}%">
            </div>
          </div>
          @if($totalHours == 0)
            <p class="text-amber-600 text-xs mt-2 font-medium">Ready to log your first hours!</p>
          @endif
        </div>
      </div>
    </div>

    <!-- Status Card - Enhanced with level system -->
    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group border border-gray-100">
      <div class="flex items-center gap-4">
        <div class="bg-purple-100 p-3 rounded-xl group-hover:scale-110 transition-transform duration-300">
          <i class="bi bi-award-fill text-purple-600 text-2xl"></i>
        </div>
        <div class="flex-1">
          <p class="text-gray-500 text-sm font-medium mb-1">Volunteer Level</p>
          <h2 class="text-xl font-bold text-gray-800">
            @if($totalHours >= 50)
              <span class="text-amber-600">Champion</span>
            @elseif($totalHours >= 20)
              <span class="text-purple-600">Leader</span>
            @elseif($totalHours >= 5)
              <span class="text-blue-600">Supporter</span>
            @else
              <span class="text-emerald-600">Beginner</span>
            @endif
          </h2>
          <div class="w-full bg-gray-100 rounded-full h-2 mt-3">
            <div class="bg-purple-500 h-2 rounded-full transition-all duration-1000 ease-out" 
                 style="width: {{ min(($totalHours / 50) * 100, 100) }}%">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Enhanced Quick Actions - Improved Color Psychology -->
  <div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      @if(auth()->user()->canCreateEvents())
        <a href="{{ route('events.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white p-5 rounded-xl text-center transition-all duration-300 transform hover:scale-105 hover:shadow-lg group border border-emerald-500">
          <i class="bi bi-plus-circle text-2xl mb-3 group-hover:scale-110 transition-transform duration-300"></i>
          <p class="font-semibold">Create Event</p>
          <p class="text-emerald-100 text-xs mt-1">Organize volunteer activity</p>
        </a>
      @else
        <a href="{{ route('organizer.verification.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white p-5 rounded-xl text-center transition-all duration-300 transform hover:scale-105 hover:shadow-lg group border border-amber-500">
          <i class="bi bi-patch-check text-2xl mb-3 group-hover:scale-110 transition-transform duration-300"></i>
          <p class="font-semibold">Become Organizer</p>
          <p class="text-amber-100 text-xs mt-1">Get verified to create events</p>
        </a>
      @endif
      
      <a href="{{ route('events.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-5 rounded-xl text-center transition-all duration-300 transform hover:scale-105 hover:shadow-lg group border border-blue-500">
        <i class="bi bi-search text-2xl mb-3 group-hover:scale-110 transition-transform duration-300"></i>
        <p class="font-semibold">Browse Events</p>
        <p class="text-blue-100 text-xs mt-1">Find opportunities</p>
      </a>
      
      <a href="{{ route('volunteers') }}" class="bg-purple-600 hover:bg-purple-700 text-white p-5 rounded-xl text-center transition-all duration-300 transform hover:scale-105 hover:shadow-lg group border border-purple-500">
        <i class="bi bi-people text-2xl mb-3 group-hover:scale-110 transition-transform duration-300"></i>
        <p class="font-semibold">Volunteer Hub</p>
        <p class="text-purple-100 text-xs mt-1">Explore all activities</p>
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Enhanced Events Section - More Prominent -->
    <div class="bg-white rounded-2xl shadow-lg p-6 lg:col-span-2 border border-gray-100">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
          @if(auth()->user()->isVerifiedOrganizer())
            Your Organized Events
          @else
            Your Registered Events
          @endif
        </h2>
        <a href="{{ auth()->user()->isVerifiedOrganizer() ? route('volunteers.organized-events') : route('volunteers.my-events') }}" 
           class="text-emerald-600 hover:text-emerald-700 text-sm font-semibold transition-all duration-300 hover:scale-105 inline-flex items-center gap-1">
          View All <i class="bi bi-arrow-right transition-transform duration-300 group-hover:translate-x-1"></i>
        </a>
      </div>
      
      <div class="space-y-4">
        @forelse($events as $event)
          <div class="border border-gray-200 rounded-xl p-5 hover:border-emerald-300 hover:shadow-md transition-all duration-300 group event-card bg-white">
            <div class="flex justify-between items-start">
              <div class="flex-1">
                <h3 class="font-bold text-gray-800 group-hover:text-emerald-600 transition-colors duration-300 text-lg mb-2">{{ $event->title }}</h3>
                
                <!-- Enhanced Event Meta Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i class="bi bi-calendar text-emerald-500"></i>
                    <span>{{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}</span>
                  </div>
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i class="bi bi-clock text-emerald-500"></i>
                    <span>{{ \Carbon\Carbon::parse($event->date)->format('g:i A') }}</span>
                  </div>
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i class="bi bi-geo-alt text-emerald-500"></i>
                    <span class="truncate">{{ $event->location }}</span>
                  </div>
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i class="bi bi-person text-emerald-500"></i>
                    <span>By {{ $event->organizer->name ?? 'Organizer' }}</span>
                  </div>
                </div>
                
                <!-- Enhanced Status & Capacity Section -->
                <div class="flex flex-wrap items-center gap-3 mt-4">
                  @if(auth()->user()->isVerifiedOrganizer())
                    <span class="bg-emerald-100 text-emerald-800 px-3 py-1.5 rounded-full text-sm font-semibold inline-flex items-center gap-1">
                      <i class="bi bi-people"></i>
                      {{ $event->volunteers_count ?? 0 }} registered
                    </span>
                  @else
                    <span class="bg-blue-100 text-blue-800 px-3 py-1.5 rounded-full text-sm font-semibold inline-flex items-center gap-1">
                      <i class="bi bi-check-circle"></i>
                      You're Registered
                    </span>
                  @endif
                  
                  <!-- Prominent Capacity Information -->
                  <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-full">
                    <div class="flex items-center gap-2">
                      <span class="text-sm font-semibold text-gray-700">{{ $event->current_volunteers }}/{{ $event->required_volunteers }}</span>
                      <span class="text-xs text-gray-500">volunteers</span>
                    </div>
                    <div class="w-16 bg-gray-200 rounded-full h-2 overflow-hidden">
                      <div class="bg-emerald-500 h-2 rounded-full transition-all duration-1000" 
                           style="width: {{ ($event->current_volunteers / $event->required_volunteers) * 100 }}%">
                      </div>
                    </div>
                  </div>
                  
                  <!-- Very Prominent Available Spots -->
                  <div class="bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-full">
                    <span class="text-sm font-bold text-amber-700">
                      {{ $event->required_volunteers - $event->current_volunteers }} spots available
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="text-center py-12 text-gray-500">
            <div class="mb-4 inline-flex rounded-2xl bg-emerald-50 p-4">
              <i class="bi bi-calendar-x text-3xl text-emerald-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">
              @if(auth()->user()->isVerifiedOrganizer())
                No Events Organized Yet
              @else
                No Events Registered Yet
              @endif
            </h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">
              @if(auth()->user()->isVerifiedOrganizer())
                Start organizing your first volunteer event and make a difference in your community!
              @else
                Explore available events and start your volunteer journey today!
              @endif
            </p>
            <a href="{{ route('events.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg inline-flex items-center gap-2">
              <i class="bi bi-search"></i>
              Browse Events
            </a>
          </div>
        @endforelse
      </div>
    </div>

    <!-- Enhanced Right Column -->
    <div class="space-y-8">
      <!-- Enhanced Impact Progress -->
      <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Your Impact Progress</h2>
        <div class="space-y-6">
          <div>
            <div class="flex justify-between items-center text-sm text-gray-600 mb-3">
              <span class="font-semibold">Volunteer Hours</span>
              <span class="font-bold text-amber-600">
                @if($totalHours > 0)
                  {{ $totalHours }} hrs
                @else
                  Start logging!
                @endif
              </span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
              <div class="bg-amber-500 h-3 rounded-full transition-all duration-1000 ease-out" 
                   style="width: {{ min(($totalHours / 100) * 100, 100) }}%">
              </div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2">
              <span>Beginner</span>
              <span>Expert (100+ hrs)</span>
            </div>
          </div>
          
          <div>
            <div class="flex justify-between items-center text-sm text-gray-600 mb-3">
              <span class="font-semibold">Events Participated</span>
              <span class="font-bold text-blue-600">{{ $totalVolunteers }} events</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
              <div class="bg-blue-500 h-3 rounded-full transition-all duration-1000 ease-out" 
                   style="width: {{ min(($totalVolunteers / 20) * 100, 100) }}%">
              </div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2">
              <span>Newcomer</span>
              <span>Veteran (20+ events)</span>
            </div>
          </div>
          
          <!-- Enhanced Level Display -->
          <div class="pt-4 border-t border-gray-200">
            <div class="text-center">
              <div class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-100 to-blue-100 px-4 py-2 rounded-full mb-3">
                <i class="bi bi-award-fill text-purple-600"></i>
                <span class="text-sm font-semibold text-gray-700">Current Level</span>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-2">
                @if($totalHours >= 50)
                  <span class="text-amber-600">🏆 Community Champion</span>
                @elseif($totalHours >= 20)
                  <span class="text-purple-600">⭐ Volunteer Leader</span>
                @elseif($totalHours >= 5)
                  <span class="text-blue-600">💚 Active Supporter</span>
                @else
                  <span class="text-emerald-600">🌱 Getting Started</span>
                @endif
              </h3>
              <p class="text-xs text-gray-500">
                @if($totalHours < 5)
                  Complete your first event to level up!
                @elseif($totalHours < 20)
                  {{ 20 - $totalHours }} more hours to reach Leader
                @elseif($totalHours < 50)
                  {{ 50 - $totalHours }} more hours to become Champion
                @else
                  You're making a huge impact!
                @endif
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Enhanced Quick Links -->
      <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Quick Links</h2>
        <div class="space-y-3">
          @if(auth()->user()->isVerifiedOrganizer())
            <a href="{{ route('volunteers.organized-events') }}" class="flex items-center gap-4 p-4 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-all duration-300 group border border-emerald-200">
              <div class="bg-emerald-100 p-3 rounded-lg group-hover:scale-110 transition-transform duration-300">
                <i class="bi bi-list-check text-emerald-600 text-xl"></i>
              </div>
              <div class="flex-1">
                <p class="font-semibold text-gray-800">Manage Events</p>
                <p class="text-sm text-gray-600">View your organized events</p>
              </div>
              <i class="bi bi-chevron-right text-gray-400 group-hover:text-emerald-600 transition-colors duration-300"></i>
            </a>
          @endif
          
          @if(!auth()->user()->isVerifiedOrganizer() && !auth()->user()->hasPendingVerification())
            <a href="{{ route('organizer.verification.create') }}" class="flex items-center gap-4 p-4 bg-amber-50 rounded-xl hover:bg-amber-100 transition-all duration-300 group border border-amber-200">
              <div class="bg-amber-100 p-3 rounded-lg group-hover:scale-110 transition-transform duration-300">
                <i class="bi bi-patch-check text-amber-600 text-xl"></i>
              </div>
              <div class="flex-1">
                <p class="font-semibold text-gray-800">Become Organizer</p>
                <p class="text-sm text-gray-600">Get verified to create events</p>
              </div>
              <i class="bi bi-chevron-right text-gray-400 group-hover:text-amber-600 transition-colors duration-300"></i>
            </a>
          @endif
          
          <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-all duration-300 group border border-blue-200">
            <div class="bg-blue-100 p-3 rounded-lg group-hover:scale-110 transition-transform duration-300">
              <i class="bi bi-person-gear text-blue-600 text-xl"></i>
            </div>
            <div class="flex-1">
              <p class="font-semibold text-gray-800">Profile Settings</p>
              <p class="text-sm text-gray-600">Update account information</p>
            </div>
            <i class="bi bi-chevron-right text-gray-400 group-hover:text-blue-600 transition-colors duration-300"></i>
          </a>

          <!-- New Feature Suggestion: Message Center -->
          @if(auth()->user()->isVerifiedOrganizer())
            <div class="flex items-center gap-4 p-4 bg-purple-50 rounded-xl border border-purple-200">
              <div class="bg-purple-100 p-3 rounded-lg">
                <i class="bi bi-chat-dots text-purple-600 text-xl"></i>
              </div>
              <div class="flex-1">
                <p class="font-semibold text-gray-800">Message Volunteers</p>
                <p class="text-sm text-gray-600">Coordinate with your team</p>
              </div>
              <span class="text-xs bg-purple-600 text-white px-2 py-1 rounded-full">Coming Soon</span>
            </div>
          @endif
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
  transform: translateY(-4px);
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Enhanced progress animations */
.progress-ring {
  transition: stroke-dashoffset 1s ease-in-out;
}

/* Color psychology enhancements */
.bg-growth { background-color: #10b981; }
.bg-trust { background-color: #3b82f6; }
.bg-energy { background-color: #f59e0b; }
.bg-achievement { background-color: #8b5cf6; }
</style>

@endsection