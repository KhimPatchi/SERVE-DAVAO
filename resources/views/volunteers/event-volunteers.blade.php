@extends('layouts.sidebar.sidebar')
@section('content')

<div class="p-6 bg-gray-50 min-h-screen" id="event-volunteers">
  
  <!-- Header -->
  <div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Event Volunteers</h1>
    <div class="flex items-center gap-4">
      <div class="text-gray-600">Hello, <span class="font-semibold">{{ Auth::user()->name }}</span></div>
      <img src="{{ Auth::user()->avatar ?? asset('assets/img/default-avatar.png') }}" alt="Profile" class="w-12 h-12 rounded-full object-cover">
    </div>
  </div>

  <!-- Event Details Card -->
  <div class="bg-white rounded-xl shadow-md p-6 mb-8 border-l-4 border-purple-500">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ $event->title }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
      <div class="flex items-center">
        <i class="bi bi-calendar-event mr-2 text-purple-600"></i>
        <span>{{ $event->date->format('M j, Y g:i A') }}</span>
      </div>
      <div class="flex items-center">
        <i class="bi bi-geo-alt mr-2 text-purple-600"></i>
        <span>{{ $event->location }}</span>
      </div>
      <div class="flex items-center">
        <i class="bi bi-people mr-2 text-purple-600"></i>
        <span>{{ $event->current_volunteers }}/{{ $event->required_volunteers }} volunteers</span>
      </div>
    </div>
  </div>

  <!-- Volunteers List -->
  <div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-xl font-semibold text-gray-800">Registered Volunteers ({{ $volunteers->count() }})</h2>
    </div>

    @if($volunteers->count() > 0)
      <div class="space-y-4">
        @foreach($volunteers as $volunteerRegistration)
          <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition group">
            <div class="flex items-center space-x-4">
              <!-- Volunteer Avatar - FIXED: Always use default avatar -->
              <div class="relative">
                <img src="{{ asset('assets/img/default-avatar.png') }}" 
                     alt="{{ $volunteerRegistration->volunteer->name }}" 
                     class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 group-hover:border-purple-300 transition-colors duration-300">
                <div class="absolute -bottom-1 -right-1 bg-green-500 w-4 h-4 rounded-full border-2 border-white"></div>
              </div>
              
              <!-- Volunteer Info -->
              <div>
                <h4 class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors duration-300">
                  {{ $volunteerRegistration->volunteer->name ?? 'Unknown User' }}
                </h4>
                <p class="text-sm text-gray-500 flex items-center gap-1">
                  <i class="bi bi-envelope"></i>
                  {{ $volunteerRegistration->volunteer->email ?? 'No email' }}
                </p>
                @if($volunteerRegistration->volunteer->phone)
                <p class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                  <i class="bi bi-telephone"></i>
                  {{ $volunteerRegistration->volunteer->phone }}
                </p>
                @endif
              </div>
            </div>

            <!-- Registration Details -->
            <div class="text-right">
              <div class="flex items-center gap-3">
                <!-- Status Badge -->
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                  @if($volunteerRegistration->status === 'registered') bg-green-100 text-green-800
                  @elseif($volunteerRegistration->status === 'attended') bg-blue-100 text-blue-800
                  @elseif($volunteerRegistration->status === 'cancelled') bg-red-100 text-red-800
                  @else bg-gray-100 text-gray-800 @endif">
                  <i class="bi 
                    @if($volunteerRegistration->status === 'registered') bi-check-circle
                    @elseif($volunteerRegistration->status === 'attended') bi-person-check
                    @elseif($volunteerRegistration->status === 'cancelled') bi-x-circle
                    @else bi-question-circle @endif mr-1"></i>
                  {{ ucfirst($volunteerRegistration->status) }}
                </span>

                <!-- Hours Volunteered -->
                @if($volunteerRegistration->hours_volunteered > 0)
                <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                  <i class="bi bi-clock mr-1"></i>
                  {{ $volunteerRegistration->hours_volunteered }}h
                </span>
                @endif
              </div>
              
              <div class="text-xs text-gray-500 mt-2">
                Registered: {{ $volunteerRegistration->created_at->format('M j, Y') }}
              </div>
              @if($volunteerRegistration->updated_at->ne($volunteerRegistration->created_at))
              <div class="text-xs text-gray-400">
                Updated: {{ $volunteerRegistration->updated_at->format('M j, Y') }}
              </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      <!-- Volunteer Stats -->
      <div class="mt-8 pt-6 border-t border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="text-center p-4 bg-green-50 rounded-xl">
            <div class="text-2xl font-bold text-green-600">{{ $volunteers->where('status', 'registered')->count() }}</div>
            <div class="text-sm text-gray-600">Registered</div>
          </div>
          <div class="text-center p-4 bg-blue-50 rounded-xl">
            <div class="text-2xl font-bold text-blue-600">{{ $volunteers->where('status', 'attended')->count() }}</div>
            <div class="text-sm text-gray-600">Attended</div>
          </div>
          <div class="text-center p-4 bg-purple-50 rounded-xl">
            <div class="text-2xl font-bold text-purple-600">{{ $volunteers->sum('hours_volunteered') }}</div>
            <div class="text-sm text-gray-600">Total Hours</div>
          </div>
          <div class="text-center p-4 bg-orange-50 rounded-xl">
            <div class="text-2xl font-bold text-orange-600">
              @php
                $avgHours = $volunteers->where('hours_volunteered', '>', 0)->avg('hours_volunteered');
              @endphp
              {{ $avgHours ? number_format($avgHours, 1) : '0' }}
            </div>
            <div class="text-sm text-gray-600">Avg Hours</div>
          </div>
        </div>
      </div>

    @else
      <!-- Empty State -->
      <div class="text-center py-12">
        <div class="bg-purple-50 rounded-full p-6 inline-flex mb-4">
          <i class="bi bi-people text-4xl text-purple-500"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-800 mb-3">No Volunteers Yet</h3>
        <p class="text-gray-600 mb-6 max-w-md mx-auto">
          No one has registered for this event yet. Share the event link to attract volunteers!
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
          <button onclick="copyEventLink()" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors duration-300 font-medium">
            <i class="bi bi-link-45deg mr-2"></i>Copy Event Link
          </button>
          <button class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-300 font-medium">
            <i class="bi bi-share mr-2"></i>Share Event
          </button>
        </div>
      </div>
    @endif
  </div>

  <!-- Quick Actions -->
  <div class="mt-6 flex flex-wrap gap-3">
    <a href="{{ route('volunteers.organized-events') }}" 
       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-300 font-medium">
      <i class="bi bi-arrow-left mr-2"></i>Back to My Events
    </a>
    <a href="{{ route('events.show', $event) }}" 
       class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors duration-300 font-medium">
      <i class="bi bi-eye mr-2"></i>View Event Page
    </a>
  </div>

</div>

<!-- JavaScript for Copy Event Link -->
<script>
function copyEventLink() {
  const eventUrl = "{{ route('events.show', $event) }}";
  navigator.clipboard.writeText(eventUrl).then(() => {
    // Show success message
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-check2 mr-2"></i>Copied!';
    button.classList.remove('bg-purple-600', 'hover:bg-purple-700');
    button.classList.add('bg-green-600', 'hover:bg-green-700');
    
    setTimeout(() => {
      button.innerHTML = originalText;
      button.classList.remove('bg-green-600', 'hover:bg-green-700');
      button.classList.add('bg-purple-600', 'hover:bg-purple-700');
    }, 2000);
  }).catch(err => {
    console.error('Failed to copy: ', err);
    alert('Failed to copy event link');
  });
}
</script>

<style>
.group:hover .group-hover\:border-purple-300 {
  border-color: #c084fc;
}
</style>

@endsection