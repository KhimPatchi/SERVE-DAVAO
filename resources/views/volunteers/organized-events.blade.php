@extends('layouts.sidebar.sidebar')
@section('content')

<div class="p-6 bg-gray-50 min-h-screen" id="organized-events">
  
  <!-- Header Section -->
  <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-800 mb-2">My Organized Events</h1>
      <p class="text-gray-600">Manage current events and track your event history</p>
    </div>
    
    <!-- Search and Create Button -->
    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
      <div class="relative">
        <input type="text" placeholder="Search events..." class="pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 w-full lg:w-64 transition-all duration-300 bg-white/80 backdrop-blur-sm">
        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
      </div>
      <a href="{{ route('events.create') }}" 
         class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg font-semibold whitespace-nowrap">
        <i class="bi bi-plus-circle mr-2"></i>
        Create Event
      </a>
    </div>
  </div>

  <!-- Event History Toggle -->
  <div class="mb-6">
    <div class="bg-white rounded-2xl shadow-sm p-4">
      <div class="flex flex-wrap gap-4 items-center justify-between">
        <div class="flex items-center gap-4">
          <h3 class="text-lg font-semibold text-gray-800">Event Timeline</h3>
          <div class="flex bg-gray-100 rounded-lg p-1">
            <button id="showCurrentEvents" class="px-4 py-2 rounded-md bg-emerald-500 text-white text-sm font-medium transition-all duration-300 transform hover:scale-105">
              Current Events
            </button>
            <button id="showEventHistory" class="px-4 py-2 rounded-md text-gray-600 hover:text-gray-800 text-sm font-medium transition-all duration-300 transform hover:scale-105">
              Event History
            </button>
          </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="flex items-center gap-6 text-sm">
          <div class="text-center">
            <div class="text-2xl font-bold text-emerald-600">{{ $currentEventsCount }}</div>
            <div class="text-gray-500">Active</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $completedEventsCount }}</div>
            <div class="text-gray-500">Completed</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $totalVolunteersCount }}</div>
            <div class="text-gray-500">Total Volunteers</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Current Events Section -->
  <div id="currentEventsSection" class="events-section transition-all duration-500 ease-in-out transform">
    @if($currentEvents->count() > 0)
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
          <i class="bi bi-lightning-fill text-emerald-500"></i>
          Active & Upcoming Events
          <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-medium">
            {{ $currentEvents->count() }} events
          </span>
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
          @foreach($currentEvents as $event)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden event-card group border-l-4 border-emerald-500 transition-all duration-500 hover:scale-105">
              <!-- Event Header -->
              <div class="relative h-40 bg-gradient-to-r from-emerald-500 to-emerald-600 overflow-hidden">
                @if($event->image)
                  <img src="{{ $event->image }}" alt="{{ $event->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                @else
                  <div class="w-full h-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                    <i class="bi bi-calendar-event text-white text-4xl opacity-80"></i>
                  </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                
                <!-- Status Badge -->
                <div class="absolute top-4 left-4">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-500 text-white backdrop-blur-sm transition-all duration-300 group-hover:scale-110">
                    <i class="bi bi-play-circle mr-1"></i>
                    Active
                  </span>
                </div>
                
                <!-- Event Title -->
                <div class="absolute bottom-4 left-4 right-4">
                  <h3 class="text-white font-bold text-lg line-clamp-2 transition-all duration-300 group-hover:text-emerald-200">{{ $event->title }}</h3>
                </div>
              </div>

              <!-- Event Content -->
              <div class="p-6">
                <p class="text-gray-600 text-sm mb-4 line-clamp-2 transition-colors duration-300 group-hover:text-gray-700">{{ Str::limit($event->description, 100) }}</p>

                <!-- Event Meta -->
                <div class="space-y-3 mb-4">
                  <div class="flex items-center text-sm text-gray-600 transition-colors duration-300 group-hover:text-gray-700">
                    <i class="bi bi-geo-alt mr-3 text-emerald-500 transition-transform duration-300 group-hover:scale-110"></i>
                    <span class="line-clamp-1">{{ $event->location }}</span>
                  </div>
                  <div class="flex items-center text-sm text-gray-600 transition-colors duration-300 group-hover:text-gray-700">
                    <i class="bi bi-calendar-event mr-3 text-emerald-500 transition-transform duration-300 group-hover:scale-110"></i>
                    <span>{{ $event->date->format('M j, Y') }}</span>
                  </div>
                  <div class="flex items-center text-sm text-gray-600 transition-colors duration-300 group-hover:text-gray-700">
                    <i class="bi bi-clock mr-3 text-emerald-500 transition-transform duration-300 group-hover:scale-110"></i>
                    <span>{{ $event->date->format('g:i A') }}</span>
                  </div>
                </div>

                <!-- Volunteer Progress -->
                <div class="mb-4">
                  <div class="flex justify-between items-center text-xs text-gray-500 mb-2">
                    <span class="font-medium">Volunteer Progress</span>
                    <span class="font-semibold">{{ $event->current_volunteers }}/{{ $event->required_volunteers }}</span>
                  </div>
                  <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                    @php
                      $completionPercentage = $event->required_volunteers > 0 ? ($event->current_volunteers / $event->required_volunteers) * 100 : 0;
                    @endphp
                    <div class="h-2 rounded-full bg-emerald-500 transition-all duration-1000 ease-out" style="width: {{ $completionPercentage }}%"></div>
                  </div>
                  <div class="flex justify-between text-xs text-gray-400 mt-1">
                    <span>{{ $event->required_volunteers - $event->current_volunteers }} spots left</span>
                    <span>{{ number_format($completionPercentage, 1) }}% filled</span>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                  <a href="{{ route('volunteers.event-volunteers', $event->id) }}" 
                     class="inline-flex items-center px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm rounded-lg transition-all duration-300 transform hover:scale-105 font-medium group/btn">
                    <i class="bi bi-people mr-2 transition-transform duration-300 group-hover/btn:translate-x-1"></i>
                    Volunteers
                  </a>
                  <a href="{{ route('events.show', $event) }}" 
                     class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition-all duration-300 transform hover:scale-105 font-medium group/btn">
                    <i class="bi bi-eye mr-2 transition-transform duration-300 group-hover/btn:translate-x-1"></i>
                    View
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @else
      <!-- No Current Events -->
      <div class="bg-white rounded-2xl shadow-lg p-12 text-center transition-all duration-500 hover:scale-105">
        <div class="max-w-md mx-auto">
          <div class="bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-full p-8 inline-flex mb-4 transition-all duration-500 hover:scale-110">
            <i class="bi bi-calendar-plus text-4xl text-emerald-600"></i>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-3">No Active Events</h3>
          <p class="text-gray-600 mb-6">Create your first event to get started!</p>
          <a href="{{ route('events.create') }}" 
             class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg font-semibold group">
            <i class="bi bi-plus-circle mr-2 transition-transform duration-300 group-hover:rotate-90"></i>
            Create Event
          </a>
        </div>
      </div>
    @endif
  </div>

  <!-- Event History Section -->
  <div id="eventHistorySection" class="events-section transition-all duration-500 ease-in-out transform opacity-0 scale-95 absolute top-0 left-0 w-full">
    @if($pastEvents->count() > 0)
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
          <i class="bi bi-clock-history text-blue-500 transition-transform duration-500"></i>
          Event History
          <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium transition-all duration-500">
            {{ $pastEvents->count() }} past events
          </span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
          @foreach($pastEvents as $event)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden event-card group border-l-4 border-gray-400 transition-all duration-500 hover:scale-105">
              <!-- Event Header -->
              <div class="relative h-40 bg-gradient-to-r from-gray-500 to-gray-600 overflow-hidden">
                @if($event->image)
                  <img src="{{ $event->image }}" alt="{{ $event->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                @else
                  <div class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                    <i class="bi bi-calendar-event text-white text-4xl opacity-80"></i>
                  </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                
                <!-- Status Badge -->
                <div class="absolute top-4 left-4">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-500 text-white transition-all duration-300 group-hover:scale-110">
                    <i class="bi bi-check-circle mr-1"></i>
                    Completed
                  </span>
                </div>
                
                <!-- Event Title -->
                <div class="absolute bottom-4 left-4 right-4">
                  <h3 class="text-white font-bold text-lg line-clamp-2 transition-all duration-300 group-hover:text-gray-200">{{ $event->title }}</h3>
                </div>
              </div>

              <!-- Event Content -->
              <div class="p-6">
                <p class="text-gray-600 text-sm mb-4 line-clamp-2 transition-colors duration-300 group-hover:text-gray-700">{{ Str::limit($event->description, 100) }}</p>

                <!-- Event Meta -->
                <div class="space-y-3 mb-4">
                  <div class="flex items-center text-sm text-gray-600 transition-colors duration-300 group-hover:text-gray-700">
                    <i class="bi bi-geo-alt mr-3 text-blue-500 transition-transform duration-300 group-hover:scale-110"></i>
                    <span class="line-clamp-1">{{ $event->location }}</span>
                  </div>
                  <div class="flex items-center text-sm text-gray-500 transition-colors duration-300 group-hover:text-gray-600">
                    <i class="bi bi-calendar-event mr-3 text-blue-500 transition-transform duration-300 group-hover:scale-110"></i>
                    <span>{{ $event->date->format('M j, Y') }}</span>
                  </div>
                  <div class="flex items-center text-sm text-gray-500 transition-colors duration-300 group-hover:text-gray-600">
                    <i class="bi bi-clock mr-3 text-blue-500 transition-transform duration-300 group-hover:scale-110"></i>
                    <span>{{ $event->date->format('g:i A') }}</span>
                  </div>
                </div>

                <!-- Volunteer Progress -->
                <div class="mb-4">
                  <div class="flex justify-between items-center text-xs text-gray-500 mb-2">
                    <span class="font-medium">Volunteer Progress</span>
                    <span class="font-semibold">{{ $event->current_volunteers }}/{{ $event->required_volunteers }}</span>
                  </div>
                  <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                    @php
                      $completionPercentage = $event->required_volunteers > 0 ? ($event->current_volunteers / $event->required_volunteers) * 100 : 0;
                    @endphp
                    <div class="h-2 rounded-full bg-blue-400 transition-all duration-1000 ease-out" style="width: {{ $completionPercentage }}%"></div>
                  </div>
                  <div class="flex justify-between text-xs text-gray-400 mt-1">
                    <span>{{ $event->required_volunteers - $event->current_volunteers }} spots left</span>
                    <span>{{ number_format($completionPercentage, 1) }}% filled</span>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                  <a href="{{ route('volunteers.event-volunteers', $event->id) }}" 
                     class="inline-flex items-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm rounded-lg transition-all duration-300 transform hover:scale-105 font-medium group/btn">
                    <i class="bi bi-people mr-2 transition-transform duration-300 group-hover/btn:translate-x-1"></i>
                    Volunteers
                  </a>
                  <a href="{{ route('events.show', $event) }}" 
                     class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition-all duration-300 transform hover:scale-105 font-medium group/btn">
                    <i class="bi bi-eye mr-2 transition-transform duration-300 group-hover/btn:translate-x-1"></i>
                    View
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <!-- History Summary -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mt-8 transition-all duration-500 hover:scale-105">
          <h3 class="text-xl font-bold text-gray-800 mb-4">Event History Summary</h3>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center p-4 bg-blue-50 rounded-xl transition-all duration-500 hover:scale-110">
              <div class="text-2xl font-bold text-blue-600">{{ $totalEventsCount }}</div>
              <div class="text-sm text-gray-600">Total Events</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-xl transition-all duration-500 hover:scale-110">
              <div class="text-2xl font-bold text-green-600">{{ $successfulEventsCount }}</div>
              <div class="text-sm text-gray-600">Successful Events</div>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-xl transition-all duration-500 hover:scale-110">
              <div class="text-2xl font-bold text-purple-600">{{ $totalVolunteersHistory }}</div>
              <div class="text-sm text-gray-600">Total Volunteers</div>
            </div>
            <div class="text-center p-4 bg-orange-50 rounded-xl transition-all duration-500 hover:scale-110">
              <div class="text-2xl font-bold text-orange-600">
                @php
                  $averageParticipation = $totalEventsCount > 0 ? round(($totalVolunteersHistory / $totalEventsCount) * 10) : 0;
                @endphp
                {{ $averageParticipation }}%
              </div>
              <div class="text-sm text-gray-600">Avg. Participation</div>
            </div>
          </div>
        </div>
      </div>
    @else
      <!-- Empty History State -->
      <div class="bg-white rounded-2xl shadow-lg p-12 text-center transition-all duration-500 hover:scale-105">
        <div class="max-w-md mx-auto">
          <div class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-full p-8 inline-flex mb-4 transition-all duration-500 hover:scale-110">
            <i class="bi bi-clock-history text-4xl text-blue-600"></i>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-3">No Event History Yet</h3>
          <p class="text-gray-600 mb-6">Your completed events will appear here.</p>
          <a href="{{ route('events.create') }}" 
             class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg font-semibold group">
            <i class="bi bi-plus-circle mr-2 transition-transform duration-300 group-hover:rotate-90"></i>
            Create Event
          </a>
        </div>
      </div>
    @endif
  </div>
</div>

<!-- Enhanced JavaScript for Smooth Animated Transitions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const showCurrentEvents = document.getElementById('showCurrentEvents');
  const showEventHistory = document.getElementById('showEventHistory');
  const currentEventsSection = document.getElementById('currentEventsSection');
  const eventHistorySection = document.getElementById('eventHistorySection');

  let isAnimating = false;

  function switchToCurrentEvents() {
    if (isAnimating) return;
    isAnimating = true;

    // Update button states
    showCurrentEvents.classList.add('bg-emerald-500', 'text-white', 'scale-105');
    showCurrentEvents.classList.remove('text-gray-600', 'scale-100');
    showEventHistory.classList.remove('bg-blue-500', 'text-white', 'scale-105');
    showEventHistory.classList.add('text-gray-600', 'scale-100');

    // Animate sections
    eventHistorySection.style.transform = 'translateX(-100%) scale(0.95)';
    eventHistorySection.style.opacity = '0';
    
    setTimeout(() => {
      eventHistorySection.classList.add('hidden');
      currentEventsSection.classList.remove('hidden');
      
      // Trigger reflow
      currentEventsSection.offsetHeight;
      
      currentEventsSection.style.transform = 'translateX(0) scale(1)';
      currentEventsSection.style.opacity = '1';
      currentEventsSection.style.position = 'relative';
      
      setTimeout(() => {
        isAnimating = false;
      }, 300);
    }, 300);
  }

  function switchToEventHistory() {
    if (isAnimating) return;
    isAnimating = true;

    // Update button states
    showEventHistory.classList.add('bg-blue-500', 'text-white', 'scale-105');
    showEventHistory.classList.remove('text-gray-600', 'scale-100');
    showCurrentEvents.classList.remove('bg-emerald-500', 'text-white', 'scale-105');
    showCurrentEvents.classList.add('text-gray-600', 'scale-100');

    // Animate sections
    currentEventsSection.style.transform = 'translateX(100%) scale(0.95)';
    currentEventsSection.style.opacity = '0';
    
    setTimeout(() => {
      currentEventsSection.classList.add('hidden');
      eventHistorySection.classList.remove('hidden');
      
      // Trigger reflow
      eventHistorySection.offsetHeight;
      
      eventHistorySection.style.transform = 'translateX(0) scale(1)';
      eventHistorySection.style.opacity = '1';
      eventHistorySection.style.position = 'relative';
      
      setTimeout(() => {
        isAnimating = false;
      }, 300);
    }, 300);
  }

  // Initialize sections
  currentEventsSection.style.transform = 'translateX(0) scale(1)';
  currentEventsSection.style.opacity = '1';
  currentEventsSection.style.position = 'relative';
  currentEventsSection.style.transition = 'all 0.5s ease-in-out';
  
  eventHistorySection.style.transform = 'translateX(100%) scale(0.95)';
  eventHistorySection.style.opacity = '0';
  eventHistorySection.style.position = 'absolute';
  eventHistorySection.style.transition = 'all 0.5s ease-in-out';

  showCurrentEvents.addEventListener('click', switchToCurrentEvents);
  showEventHistory.addEventListener('click', switchToEventHistory);

  // Start with current events visible
  switchToCurrentEvents();
});
</script>

<style>
.events-section {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.event-card {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Smooth scaling for all interactive elements */
button, a, .transition-element {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Enhanced hover effects */
.hover-lift:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>

@endsection