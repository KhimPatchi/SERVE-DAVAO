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
        @foreach($volunteers as $volunteer)
          <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
            <div class="flex items-center space-x-4">
              <img src="{{ $volunteer->volunteer->avatar ?? asset('assets/img/default-avatar.png') }}" 
                   alt="{{ $volunteer->volunteer->name }}" 
                   class="w-10 h-10 rounded-full object-cover">
              <div>
                <h4 class="font-medium text-gray-900">{{ $volunteer->volunteer->name }}</h4>
                <p class="text-sm text-gray-500">{{ $volunteer->volunteer->email }}</p>
              </div>
            </div>
            <div class="text-sm text-gray-500">
              Registered: {{ $volunteer->created_at->format('M j, Y') }}
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="text-center py-8">
        <div class="bg-purple-50 rounded-full p-4 inline-flex mb-4">
          <i class="bi bi-people text-3xl text-purple-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">No Volunteers Yet</h3>
        <p class="text-gray-600">No one has registered for this event yet.</p>
      </div>
    @endif
  </div>

  <!-- Quick Actions -->
  <div class="mt-6">
    <a href="{{ route('volunteers.organized-events') }}" 
       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200 font-medium">
      <i class="bi bi-arrow-left mr-2"></i>Back to My Events
    </a>
  </div>

</div>

@endsection