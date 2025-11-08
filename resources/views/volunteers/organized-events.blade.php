@extends('layouts.sidebar.sidebar')
@section('content')

<div class="p-6 bg-gray-50 min-h-screen" id="organized-events">
  
  <!-- Enhanced Header with Search and Actions -->
  <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4 scroll-fade-in">
    <div>
      <h1 class="text-3xl font-bold text-gray-800 mb-2">My Organized Events</h1>
      <p class="text-gray-600">Manage and track all your volunteer events in one place</p>
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

  <!-- Events Grid with Enhanced Layout -->
  <div class="mb-8">
    @if($events->count() > 0)
      <!-- Events Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 stagger-animate">
        @foreach($events as $event)
          @php
            $isPastEvent = $event->date->isPast();
            $cardClass = $isPastEvent ? 'opacity-75 grayscale hover:grayscale-0' : '';
            $borderColor = $isPastEvent ? 'border-gray-300' : 'border-emerald-500';
          @endphp
          
          <div class="bg-white rounded-2xl shadow-lg overflow-hidden event-card group border-l-4 {{ $borderColor }} {{ $cardClass }}">
            <!-- Event Header with Image -->
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
                @php
                  $statusColors = [
                    'active' => 'emerald',
                    'completed' => 'blue', 
                    'cancelled' => 'red',
                    'draft' => 'gray'
                  ];
                  $statusColor = $statusColors[$event->status] ?? 'gray';
                  
                  // Override status for past events
                  if ($isPastEvent && $event->status === 'active') {
                    $statusColor = 'gray';
                    $eventStatus = 'completed';
                    $statusIcon = 'check-circle';
                  } else {
                    $eventStatus = $event->status;
                    $statusIcon = $event->status === 'active' ? 'play-circle' : ($event->status === 'completed' ? 'check-circle' : 'pause-circle');
                  }
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $statusColor }}-500 text-white backdrop-blur-sm">
                  <i class="bi bi-{{ $statusIcon }} mr-1"></i>
                  {{ $isPastEvent && $event->status === 'active' ? 'Completed' : ucfirst($eventStatus) }}
                </span>
              </div>
              
              <!-- Past Event Badge -->
              @if($isPastEvent)
                <div class="absolute top-4 right-4">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-600 text-white backdrop-blur-sm">
                    <i class="bi bi-clock-history mr-1"></i>
                    Past Event
                  </span>
                </div>
              @else
                <!-- Quick Actions for future events -->
                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                  <div class="flex space-x-1">
                    <button class="bg-white/20 backdrop-blur-sm text-white p-2 rounded-lg hover:bg-white/30 transition-colors" title="Event Options">
                      <i class="bi bi-three-dots"></i>
                    </button>
                  </div>
                </div>
              @endif
              
              <!-- Event Title Overlay -->
              <div class="absolute bottom-4 left-4 right-4">
                <h3 class="text-white font-bold text-lg line-clamp-2 group-hover:text-emerald-200 transition-colors">{{ $event->title }}</h3>
              </div>
            </div>

            <!-- Event Content -->
            <div class="p-6">
              <!-- Description -->
              <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                {{ Str::limit($event->description, 100) }}
              </p>

              <!-- Event Meta -->
              <div class="space-y-3 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                  <i class="bi bi-geo-alt mr-3 text-emerald-500 transition-transform duration-300 group-hover:scale-110"></i>
                  <span class="line-clamp-1">{{ $event->location }}</span>
                </div>
                <div class="flex items-center text-sm {{ $isPastEvent ? 'text-gray-400' : 'text-gray-600' }}">
                  <i class="bi bi-calendar-event mr-3 text-emerald-500 transition-transform duration-300 group-hover:scale-110"></i>
                  <span>{{ $event->date->format('M j, Y') }}</span>
                </div>
                <div class="flex items-center text-sm {{ $isPastEvent ? 'text-gray-400' : 'text-gray-600' }}">
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
                    $progressColor = $completionPercentage >= 80 ? 'bg-green-500' : ($completionPercentage >= 50 ? 'bg-emerald-400' : 'bg-emerald-300');
                  @endphp
                  <div class="h-2 rounded-full transition-all duration-1000 ease-out {{ $progressColor }}" 
                       style="width: {{ $completionPercentage }}%">
                  </div>
                </div>
                <div class="flex justify-between text-xs text-gray-400 mt-1">
                  <span>{{ $event->required_volunteers - $event->current_volunteers }} spots left</span>
                  <span>{{ number_format($completionPercentage, 1) }}% filled</span>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                <div class="flex space-x-2">
                  <a href="{{ route('volunteers.event-volunteers', $event->id) }}" 
                     class="inline-flex items-center px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm rounded-lg transition-all duration-300 transform hover:scale-105 font-medium group/btn">
                    <i class="bi bi-people mr-2 transition-transform duration-300 group-hover/btn:translate-x-1"></i>
                    Volunteers
                  </a>
                </div>
                
                <div class="flex space-x-2">
                  <a href="{{ route('events.show', $event) }}" 
                     class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition-all duration-300 transform hover:scale-105 font-medium group/btn">
                    <i class="bi bi-eye mr-2 transition-transform duration-300 group-hover/btn:translate-x-1"></i>
                    View
                  </a>
                  @if(!$isPastEvent)
                    <button class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition-all duration-300 transform hover:scale-105" title="Share Event">
                      <i class="bi bi-share"></i>
                    </button>
                  @endif
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Enhanced Pagination -->
      <div class="mt-12 scroll-fade-in">
        <div class="bg-white rounded-2xl shadow-sm p-6">
          <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-gray-600 text-sm">
              Showing {{ $events->firstItem() ?? '0' }} to {{ $events->lastItem() ?? '0' }} of {{ $events->total() }} events
            </p>
            {{ $events->links() }}
          </div>
        </div>
      </div>

    @else
      <!-- Enhanced Empty State -->
      <div class="bg-white rounded-2xl shadow-lg p-12 text-center scroll-fade-in">
        <div class="max-w-md mx-auto transform transition-all duration-500 hover:scale-105">
          <div class="relative mb-6">
            <div class="bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-full p-8 inline-flex mb-4">
              <i class="bi bi-calendar-plus text-4xl text-emerald-600"></i>
            </div>
            <div class="absolute -top-2 -right-2 bg-emerald-500 text-white rounded-full p-2">
              <i class="bi bi-lightning"></i>
            </div>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-3">Ready to Make an Impact?</h3>
          <p class="text-gray-600 mb-8 text-lg leading-relaxed">
            Create your first volunteer event and start building your community. It only takes a few minutes to get started.
          </p>
          <div class="space-y-4">
            <a href="{{ route('events.create') }}" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-xl font-semibold text-lg">
              <i class="bi bi-plus-circle mr-3 text-xl"></i>
              Create Event
            </a>
            <div class="text-sm text-gray-500">
              <p>Need inspiration? <a href="#" class="text-emerald-600 hover:text-emerald-700 font-medium">Browse event ideas</a></p>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>
</div>

<!-- Your existing styles and scripts -->
@endsection