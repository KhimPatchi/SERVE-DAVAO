@extends ('layouts.sidebar.sidebar')
@section ('content')

@php
  $showHistoryByDefault = request('search') && $currentEvents->isEmpty() && $pastEvents->isNotEmpty();
@endphp

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50 p-8" id="organized-events">
  
  <!-- Clean Professional Header -->
  <div class="mb-10">
    <!-- Breadcrumb -->
    <nav class="mb-6 flex items-center space-x-2 text-sm text-gray-400">
      <a href="{{ route('dashboard') }}" class="transition-colors hover:text-emerald-600 font-medium">
        Dashboard
      </a>
      <span>/</span>
      <span class="text-gray-700 font-semibold">Organizer Dashboard</span>
    </nav>

    <!-- Title Section -->
    <div class="mb-8">
      <h1 class="text-4xl font-bold text-gray-900 mb-3 tracking-tight">My Events</h1>
      <p class="text-lg text-gray-500 font-normal">Manage and track your volunteer opportunities</p>
    </div>

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
      <!-- Search -->
      <form action="{{ route('volunteers.organized-events') }}" method="GET" class="relative flex-1 max-w-md">
        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="search" value="{{ request('search') }}" 
               placeholder="Search by title or location..." 
               class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-gray-700 placeholder-gray-400">
      </form>
      
      <!-- Create Button -->
      <a href="{{ route('events.create') }}" 
         class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
        <i class="bi bi-plus-lg"></i>
        <span>Create Event</span>
      </a>
    </div>
  </div>

  <!-- Stats Overview -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Active Events -->
    <div class="bg-white rounded-lg p-6 border border-gray-200 hover:border-emerald-300 transition-all">
      <div class="flex items-center justify-between mb-3">
        <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
          <i class="bi bi-lightning-charge-fill text-emerald-600 text-lg"></i>
        </div>
        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded">LIVE</span>
      </div>
      <div class="text-3xl font-bold text-gray-900 mb-1">{{ $currentEventsCount }}</div>
      <div class="text-sm text-gray-500 font-medium">Active Events</div>
    </div>

    <!-- Completed -->
    <div class="bg-white rounded-lg p-6 border border-gray-200 hover:border-blue-300 transition-all">
      <div class="flex items-center justify-between mb-3">
        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
          <i class="bi bi-check-circle-fill text-blue-600 text-lg"></i>
        </div>
      </div>
      <div class="text-3xl font-bold text-gray-900 mb-1">{{ $completedEventsCount }}</div>
      <div class="text-sm text-gray-500 font-medium">Completed</div>
    </div>

    <!-- Total Volunteers (example) -->
    <div class="bg-white rounded-lg p-6 border border-gray-200 hover:border-purple-300 transition-all">
      <div class="flex items-center justify-between mb-3">
        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
          <i class="bi bi-people-fill text-purple-600 text-lg"></i>
        </div>
      </div>
      <div class="text-3xl font-bold text-gray-900 mb-1">{{ $currentEvents->sum('current_volunteers') }}</div>
      <div class="text-sm text-gray-500 font-medium">Total Volunteers</div>
    </div>

    <!-- Impact Hours (example) -->
    <div class="bg-white rounded-lg p-6 border border-gray-200 hover:border-amber-300 transition-all">
      <div class="flex items-center justify-between mb-3">
        <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
          <i class="bi bi-clock-fill text-amber-600 text-lg"></i>
        </div>
      </div>
      <div class="text-3xl font-bold text-gray-900 mb-1">{{ $currentEvents->sum('duration') + $pastEvents->sum('duration') }}</div>
      <div class="text-sm text-gray-500 font-medium">Total Hours</div>
    </div>
  </div>

  <!-- Tab Navigation -->
  <div class="bg-white rounded-lg border border-gray-200 p-1.5 inline-flex mb-8 shadow-sm">
    <button id="showCurrentEvents" 
            class="px-6 py-2.5 rounded-md text-sm font-semibold transition-all {{ !$showHistoryByDefault ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
      Active Events
    </button>
    <button id="showEventHistory" 
            class="px-6 py-2.5 rounded-md text-sm font-semibold transition-all {{ $showHistoryByDefault ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
      History
    </button>
  </div>

  <!-- Active Events Grid -->
  <div id="currentEventsSection" 
       class="events-section transition-all duration-300 {{ !$showHistoryByDefault ? 'opacity-100' : 'hidden opacity-0' }}">
    @if ($currentEvents->count() > 0)
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          @foreach ($currentEvents as $event)
            <div class="bg-white rounded-lg border border-gray-200 hover:border-gray-300 hover:shadow-lg transition-all group">
              
              <!-- Event Image -->
              <div class="relative h-48 overflow-hidden bg-gray-100 rounded-t-lg">
                @if ($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-emerald-50 to-teal-50">
                        <i class="bi bi-calendar-event text-5xl text-emerald-200"></i>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full flex items-center gap-2 shadow-sm">
                  <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                  <span class="text-xs font-semibold text-emerald-700">Active</span>
                </div>

                <!-- Date Badge -->
                <div class="absolute bottom-4 right-4 bg-white/95 backdrop-blur-sm px-3 py-2 rounded-lg shadow-sm">
                  <div class="text-xs text-gray-500 font-medium mb-0.5">{{ $event->date->format('M d') }}</div>
                  <div class="text-xs text-gray-700 font-semibold">{{ $event->date->format('Y') }}</div>
                </div>
              </div>

              <!-- Event Content -->
              <div class="p-6">
                <!-- Title -->
                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition-colors line-clamp-2">
                  {{ $event->title }}
                </h3>
                
                <!-- Location -->
                <div class="flex items-center text-gray-500 text-sm mb-4">
                  <i class="bi bi-geo-alt text-gray-400 mr-1.5"></i>
                  <span class="font-medium">{{ $event->location }}</span>
                </div>

                <!-- Skills -->
                @if ($event->skills_required)
                <div class="flex flex-wrap gap-2 mb-5">
                  @foreach (array_slice(explode(',', $event->skills_required), 0, 3) as $skill)
                  <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">
                    {{ trim($skill) }}
                  </span>
                  @endforeach
                  @if (count(explode(',', $event->skills_required)) > 3)
                  <span class="px-2.5 py-1 bg-gray-100 text-gray-500 rounded text-xs font-medium">
                    +{{ count(explode(',', $event->skills_required)) - 3 }}
                  </span>
                  @endif
                </div>
                @endif

                <!-- Progress -->
                <div class="mb-5">
                  <div class="flex justify-between text-xs font-semibold text-gray-600 mb-2">
                    <span>Volunteers</span>
                    <span>{{ $event->current_volunteers }} / {{ $event->required_volunteers }}</span>
                  </div>
                  <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" 
                         style="width: {{ $event->required_volunteers > 0 ? ($event->current_volunteers / $event->required_volunteers) * 100 : 0 }}%"></div>
                  </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-4 border-t border-gray-100">
                  <a href="{{ route('events.show', $event) }}" 
                     class="flex-1 text-center py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors text-sm">
                    Manage
                  </a>
                  {{-- Scan Volunteers button â€” only for events that have started --}}
                  <a href="{{ route('organizer.attendance.scan', $event) }}"
                     class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors text-sm"
                     title="Open QR scanner to check in volunteers">
                    <i class="bi bi-qr-code-scan text-xs"></i>
                    Scan
                  </a>
                  <a href="{{ route('events.edit', $event) }}" 
                     class="px-4 py-2.5 border border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 rounded-lg transition-colors">
                    <i class="bi bi-pencil"></i>
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>

    @else
      <!-- Empty State -->
      <div class="text-center py-20 bg-white rounded-lg border-2 border-dashed border-gray-200">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="bi bi-calendar-plus text-2xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">
          @if (request('search'))
            No events found
          @else
            No active events
          @endif
        </h3>
        <p class="text-gray-500 mb-6 max-w-md mx-auto">
          @if (request('search'))
            Try adjusting your search terms
          @else
            Create your first event to start organizing volunteers
          @endif
        </p>
        <a href="{{ route('events.create') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
          <i class="bi bi-plus-lg"></i>
          <span>Create Event</span>
        </a>
      </div>
    @endif
  </div>

  <!-- History Table -->
  <div id="eventHistorySection" 
       class="events-section transition-all duration-300 {{ $showHistoryByDefault ? 'opacity-100' : 'hidden opacity-0' }}">
    @if ($pastEvents->count() > 0)
        
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Event</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Volunteers</th>
                  <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @foreach ($pastEvents as $event)
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="px-6 py-4">
                    <div class="font-semibold text-gray-900 mb-1">{{ $event->title }}</div>
                    <div class="text-sm text-gray-500 flex items-center">
                      <i class="bi bi-geo-alt text-gray-400 mr-1"></i>
                      {{ $event->location }}
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $event->date->format('M d, Y') }}</div>
                    <div class="text-xs text-gray-500">{{ $event->date->format('g:i A') }}</div>
                  </td>
                  <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 rounded-full text-sm font-bold">
                      {{ $event->volunteers_count }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <a href="{{ route('events.show', $event) }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 border border-gray-300 hover:border-gray-400 rounded-lg transition-colors">
                      <i class="bi bi-file-text text-xs"></i>
                      View Report
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

    @else
      <!-- Empty State -->
      <div class="text-center py-20 bg-white rounded-lg border-2 border-dashed border-gray-200">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="bi bi-archive text-2xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No past events</h3>
        <p class="text-gray-500">Completed events will appear here</p>
      </div>
    @endif
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const showCurrent = document.getElementById('showCurrentEvents');
  const showHistory = document.getElementById('showEventHistory');
  const currentSection = document.getElementById('currentEventsSection');
  const historySection = document.getElementById('eventHistorySection');

  function toggleView(view) {
    const incomingSection = view === 'current' ? currentSection : historySection;
    const outgoingSection = view === 'current' ? historySection : currentSection;
    
    if (!incomingSection.classList.contains('hidden')) return;

    // Update buttons
    if (view === 'current') {
      showCurrent.classList.add('bg-emerald-600', 'text-white', 'shadow-sm');
      showCurrent.classList.remove('text-gray-600', 'hover:text-gray-900', 'hover:bg-gray-50');
      
      showHistory.classList.add('text-gray-600', 'hover:text-gray-900', 'hover:bg-gray-50');
      showHistory.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
    } else {
      showHistory.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
      showHistory.classList.remove('text-gray-600', 'hover:text-gray-900', 'hover:bg-gray-50');
      
      showCurrent.classList.add('text-gray-600', 'hover:text-gray-900', 'hover:bg-gray-50');
      showCurrent.classList.remove('bg-emerald-600', 'text-white', 'shadow-sm');
    }

    // Toggle visibility
    outgoingSection.classList.add('hidden', 'opacity-0');
    outgoingSection.classList.remove('opacity-100');
    
    incomingSection.classList.remove('hidden');
    setTimeout(() => {
      incomingSection.classList.remove('opacity-0');
      incomingSection.classList.add('opacity-100');
    }, 10);
  }

  showCurrent.addEventListener('click', () => toggleView('current'));
  showHistory.addEventListener('click', () => toggleView('history'));
});
</script>
@endsection
