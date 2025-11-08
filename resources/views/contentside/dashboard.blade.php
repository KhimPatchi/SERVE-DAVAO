@extends('layouts.sidebar.sidebar') {{-- Your sidebar layout --}}
@section('content')

<div class="p-6 bg-gray-50 min-h-screen" id ="dashboard">
  
  <!-- Header -->
  <div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
    
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white shadow-lg rounded-xl p-6 flex items-center gap-4 hover:shadow-xl transition">
      <div class="bg-green-100 p-3 rounded-full">
        <i class="bi bi-people-fill text-green-600 text-2xl"></i>
      </div>
      <div>
        <p class="text-gray-500 text-sm">Total Volunteers</p>
        <h2 class="text-xl font-semibold text-gray-800">{{ $totalVolunteers }}</h2>
      </div>
    </div>

    <div class="bg-white shadow-lg rounded-xl p-6 flex items-center gap-4 hover:shadow-xl transition">
      <div class="bg-blue-100 p-3 rounded-full">
        <i class="bi bi-calendar-event text-blue-600 text-2xl"></i>
      </div>
      <div>
        <p class="text-gray-500 text-sm">Upcoming Events</p>
        <h2 class="text-xl font-semibold text-gray-800">{{ $upcomingEvents }}</h2>
      </div>
    </div>

    <div class="bg-white shadow-lg rounded-xl p-6 flex items-center gap-4 hover:shadow-xl transition">
      <div class="bg-purple-100 p-3 rounded-full">
        <i class="bi bi-clock-fill text-purple-600 text-2xl"></i>
      </div>
      <div>
        <p class="text-gray-500 text-sm">Your Hours</p>
        <h2 class="text-xl font-semibold text-gray-800">{{ $totalHours }}</h2>
      </div>
    </div>
  </div>

  <!-- Upcoming Events Section -->
  <div>
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Upcoming Events</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @forelse($events as $event)
        <div class="bg-white rounded-xl shadow-md p-5 hover:shadow-xl transition">
          <h3 class="text-lg font-semibold text-gray-800">{{ $event->title }}</h3>
          <p class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($event->date)->format('F d, Y') }}</p>
          <p class="text-gray-600 mt-2 text-sm">Volunteers Registered: {{ $event->volunteers->count() ?? 0 }}</p>
        </div>
      @empty
        <p class="text-gray-500 col-span-full">No upcoming events.</p>
      @endforelse
    </div>
  </div>

</div>

@endsection
    