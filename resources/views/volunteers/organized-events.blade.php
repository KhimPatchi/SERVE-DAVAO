@extends('layouts.sidebar.sidebar')
@section('content')

@php
  // Determine which tab should be active by default
  // If we have a search query, and no current events match, but past events DO match, show history.
  $showHistoryByDefault = request('search') && $currentEvents->isEmpty() && $pastEvents->isNotEmpty();
@endphp

<div class="px-6 py-8 md:px-10 md:py-12 bg-gray-50/50 min-h-screen" id="organized-events">
  
  <!-- Modern Header Section -->
  <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-12 gap-8">
    <div>
      <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-tight mb-2">Organizer Dashboard</h1>
      <p class="text-lg text-gray-500 font-medium tracking-wide">Manage events & optimize for matches</p>
    </div>
    
    <!-- Search and Actions -->
    <div class="flex flex-col sm:flex-row gap-4 w-full xl:w-auto items-center">
      <form action="{{ route('volunteers.organized-events') }}" method="GET" class="relative group w-full sm:w-80">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events..." class="pl-14 pr-6 h-14 border-none bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] rounded-2xl focus:ring-4 focus:ring-emerald-50/50 focus:shadow-lg w-full transition-all duration-300 placeholder-gray-400 font-medium text-gray-600">
        <button type="submit" class="absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors bg-transparent border-none p-0 cursor-pointer">
            <i class="bi bi-search text-lg"></i>
        </button>
      </form>
      <a href="{{ route('events.create') }}" 
         class="inline-flex items-center justify-center px-8 h-14 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl shadow-[0_4px_20px_-5px_rgba(16,185,129,0.4)] hover:shadow-[0_8px_25px_-8px_rgba(16,185,129,0.5)] transition-all duration-300 transform hover:-translate-y-0.5 font-bold tracking-wide group whitespace-nowrap w-full sm:w-auto">
        <i class="bi bi-plus-lg mr-2 text-lg transition-transform duration-300 group-hover:rotate-90"></i>
        Create Event
      </a>
    </div>
  </div>

  <!-- Toggle & Stats Bar -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12 items-center">
    <!-- Toggle Switch -->
    <div class="bg-white p-1.5 rounded-2xl shadow-sm inline-flex w-full sm:w-auto border border-gray-100/50">
      <button id="showCurrentEvents" 
              class="flex-1 sm:flex-none px-8 py-3.5 rounded-xl text-sm font-bold tracking-wide transition-all duration-300 {{ !$showHistoryByDefault ? 'bg-emerald-50 text-emerald-700 shadow-sm ring-1 ring-black/5' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
        <i class="bi bi-lightning-fill mr-2"></i>Active
      </button>
      <button id="showEventHistory" 
              class="flex-1 sm:flex-none px-8 py-3.5 rounded-xl text-sm font-bold tracking-wide transition-all duration-300 {{ $showHistoryByDefault ? 'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-black/5' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
        <i class="bi bi-clock-history mr-2"></i>History
      </button>
    </div>

    <!-- Stats -->
    <div class="col-span-1 lg:col-span-2 flex flex-wrap gap-5 lg:justify-end">
        <div class="bg-white px-8 py-4 rounded-3xl shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-gray-50 flex items-center gap-4 transition-transform hover:scale-105 duration-300">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 text-xl">
                <i class="bi bi-broadcast"></i>
            </div>
            <div>
                <div class="text-[11px] text-gray-400 uppercase font-black tracking-widest mb-0.5">Active</div>
                <div class="text-2xl font-black text-gray-800 leading-none">{{ $currentEventsCount }}</div>
            </div>
        </div>
        <div class="bg-white px-8 py-4 rounded-3xl shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-gray-50 flex items-center gap-4 transition-transform hover:scale-105 duration-300">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500 text-xl">
                <i class="bi bi-check-all"></i>
            </div>
            <div>
                <div class="text-[11px] text-gray-400 uppercase font-black tracking-widest mb-0.5">Completed</div>
                <div class="text-2xl font-black text-gray-800 leading-none">{{ $completedEventsCount }}</div>
            </div>
        </div>
    </div>
  </div>

  <!-- Active Events Section -->
  <div id="currentEventsSection" 
       class="events-section transition-all duration-300 ease-out transform {{ !$showHistoryByDefault ? 'opacity-100 translate-y-0' : 'hidden opacity-0 translate-y-4' }}">
    @if($currentEvents->count() > 0)
        
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
          @foreach($currentEvents as $event)
            <div class="bg-white rounded-[2.5rem] shadow-[0_8px_30px_-6px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_40px_-10px_rgba(16,185,129,0.1)] transition-all duration-300 border border-gray-100/80 group overflow-hidden">
              
              <div class="flex flex-col md:flex-row h-full">
                <!-- Image Side -->
                <div class="md:w-[280px] lg:w-[320px] relative min-h-[240px] md:min-h-0 overflow-hidden shrink-0">
                    <div class="absolute inset-0 bg-gray-900/5 group-hover:bg-transparent transition-colors duration-500 z-10"></div>
                    @if($event->image)
                        <img src="{{ $event->image }}" alt="{{ $event->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="w-full h-full bg-gray-50 flex items-center justify-center">
                            <i class="bi bi-image text-gray-200 text-5xl"></i>
                        </div>
                    @endif
                    
                    <!-- Floating Date -->
                    <div class="absolute top-5 left-5 z-20 bg-white/90 backdrop-blur-xl rounded-2xl p-3.5 text-center shadow-[0_8px_20px_-8px_rgba(0,0,0,0.15)] border border-white/50">
                        <div class="text-[10px] text-gray-500 font-black uppercase tracking-widest mb-0.5">{{ $event->date->format('M') }}</div>
                        <div class="text-2xl font-black text-gray-900 leading-none">{{ $event->date->format('d') }}</div>
                    </div>
                </div>

                <!-- Content Side -->
                <div class="flex-1 p-7 flex flex-col h-full">
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-4">
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-[11px] font-bold bg-emerald-50/80 text-emerald-600 ring-1 ring-emerald-100/50">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Recruiting
                            </span>
                            <div class="text-right">
                                <div class="text-[10px] font-black text-gray-300 uppercase tracking-widest">STATUS</div>
                                <div class="text-sm font-bold text-emerald-600">Active</div>
                            </div>
                        </div>

                        <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3 leading-snug group-hover:text-emerald-600 transition-colors">
                            {{ $event->title }}
                        </h3>
                        
                        <div class="flex items-center text-gray-500 text-sm font-medium mb-6">
                            <i class="bi bi-geo-alt-fill text-emerald-500/70 mr-2.5"></i>
                            {{ $event->location }}
                        </div>

                        <!-- ALGORITHM MATCHING DATA -->
                        @if($event->skills_required)
                        <div class="bg-indigo-50/30 rounded-2xl p-4 mb-6 border border-indigo-50/50">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="bi bi-hdd-network text-indigo-400"></i>
                                <span class="text-[10px] font-black text-indigo-300 uppercase tracking-widest">Match Profile</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $event->skills_required) as $skill)
                                <span class="px-2.5 py-1.5 bg-white border border-indigo-100/50 rounded-lg text-xs font-bold text-indigo-600/80 shadow-sm flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                                    {{ trim($skill) }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Progress & Action -->
                    <div class="pt-6 border-t border-gray-50 mt-auto">
                        <div class="flex justify-between text-xs font-bold text-gray-400 mb-2">
                            <span class="tracking-wide">Capacity Filled</span>
                            <span class="text-gray-900">{{ number_format(($event->current_volunteers / $event->required_volunteers) * 100) }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 mb-6 overflow-hidden">
                            <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-full rounded-full transition-all duration-1000" style="width: {{ $event->required_volunteers > 0 ? ($event->current_volunteers / $event->required_volunteers) * 100 : 0 }}%"></div>
                        </div>

                        <div class="flex gap-3">
                             <a href="{{ route('events.show', $event) }}" class="flex-1 py-3.5 bg-gray-900 hover:bg-black text-white rounded-xl font-bold text-sm transition-all text-center shadow-lg shadow-gray-200">
                                Manage Event
                             </a>
                             <a href="{{ route('events.edit', $event) }}" class="px-5 py-3.5 border border-gray-200 hover:border-gray-900 text-gray-400 hover:text-gray-900 rounded-xl font-bold text-sm transition-all bg-white">
                                <i class="bi bi-pencil-fill"></i>
                             </a>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

    @else
      <div class="flex flex-col items-center justify-center py-24 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 border-dashed">
        <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mb-6">
            <i class="bi bi-calendar-plus text-4xl text-emerald-500"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">
            @if(request('search'))
                No Active Events Found
            @else
                Start Your Journey
            @endif
        </h3>
        <p class="text-gray-500 mb-8 max-w-sm text-center leading-relaxed">
            @if(request('search'))
                Try adjusting your search terms or checking the history tab.
            @else
                Create your first event to start matching with volunteers automatically.
            @endif
        </p>
        <a href="{{ route('events.create') }}" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-1">
            Create Event
        </a>
      </div>
    @endif
  </div>

  <!-- Event History Section (Table Layout) -->
  <div id="eventHistorySection" 
       class="events-section transition-all duration-300 ease-out transform {{ $showHistoryByDefault ? 'opacity-100 translate-y-0' : 'hidden opacity-0 translate-y-4' }}">
    @if($pastEvents->count() > 0)
        
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/40 border-b border-gray-100">
                        <tr>
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-widest text-left pl-10">Event Details</th>
                            <th class="px-6 py-6 text-[11px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-6 text-[11px] font-black text-gray-400 uppercase tracking-widest text-center">Impact Stats</th>
                            <th class="px-8 py-6 text-right text-[11px] font-black text-gray-400 uppercase tracking-widest pr-10">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($pastEvents as $event)
                        <tr class="group hover:bg-gray-50/60 transition-colors">
                            <td class="px-8 py-6 pl-10">
                                <div class="font-bold text-gray-900 text-base mb-1 group-hover:text-emerald-700 transition-colors">{{ $event->title }}</div>
                                <div class="text-xs font-semibold text-gray-400 flex items-center gap-2">
                                    <i class="bi bi-geo-alt text-gray-300"></i>
                                    {{ $event->location }}
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="font-bold text-gray-700 text-sm">{{ $event->date->format('M d, Y') }}</div>
                                <div class="text-xs font-bold text-gray-400 mt-0.5">{{ $event->date->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex justify-center gap-3">
                                    <div class="text-center px-3 py-1.5 bg-blue-50/50 rounded-lg border border-blue-100/50 min-w-[70px]">
                                        <div class="text-[10px] text-blue-400 font-extrabold uppercase tracking-wider mb-0.5">Vols</div>
                                        <div class="font-black text-blue-700 text-sm">{{ $event->volunteers_count }}</div>
                                    </div>
                                    <div class="text-center px-3 py-1.5 bg-amber-50/50 rounded-lg border border-amber-100/50 min-w-[70px]">
                                        <div class="text-[10px] text-amber-400 font-extrabold uppercase tracking-wider mb-0.5">Rate</div>
                                        <div class="font-black text-amber-700 text-sm">100%</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right pr-10">
                                <a href="{{ route('events.show', $event) }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 hover:border-emerald-500 hover:text-emerald-600 rounded-xl text-xs font-bold text-gray-500 transition-all shadow-sm">
                                    Report
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else
      <div class="flex flex-col items-center justify-center py-24 border-2 border-gray-100 border-dashed rounded-[2.5rem] bg-white/50">
        <i class="bi bi-archive text-5xl text-gray-200 mb-6"></i>
        <h3 class="text-xl font-bold text-gray-400">
            @if(request('search'))
                No Past Events Found
            @else
                No Past Events
            @endif
        </h3>
        <p class="text-gray-400 text-sm mt-2">
            @if(request('search'))
                Try different keywords.
            @else
                Events will appear here once they are completed.
            @endif
        </p>
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

  let isAnimating = false;

  function toggleView(view) {
    if (isAnimating) return;
    
    // Determine which section is incoming and which is outgoing
    const incomingSection = view === 'current' ? currentSection : historySection;
    const outgoingSection = view === 'current' ? historySection : currentSection;
    
    // If we're already showing the requested view, do nothing
    if (!incomingSection.classList.contains('hidden')) return;
    
    isAnimating = true;

    // 1. Update Buttons immediately
    if (view === 'current') {
      showCurrent.classList.add('bg-emerald-50', 'text-emerald-700', 'shadow-sm', 'ring-1', 'ring-black/5');
      showCurrent.classList.remove('text-gray-400', 'hover:text-gray-600', 'hover:bg-gray-50');
      
      showHistory.classList.add('text-gray-400', 'hover:text-gray-600', 'hover:bg-gray-50');
      showHistory.classList.remove('bg-blue-50', 'text-blue-700', 'shadow-sm', 'ring-1', 'ring-black/5');
    } else {
      showHistory.classList.add('bg-blue-50', 'text-blue-700', 'shadow-sm', 'ring-1', 'ring-black/5');
      showHistory.classList.remove('text-gray-400', 'hover:text-gray-600', 'hover:bg-gray-50');
      
      showCurrent.classList.add('text-gray-400', 'hover:text-gray-600', 'hover:bg-gray-50');
      showCurrent.classList.remove('bg-emerald-50', 'text-emerald-700', 'shadow-sm', 'ring-1', 'ring-black/5');
    }

    // 2. Fade Out Outgoing
    outgoingSection.classList.add('opacity-0', 'translate-y-4');
    outgoingSection.classList.remove('opacity-100', 'translate-y-0');

    // 3. Wait for fade out, then switch display properties
    setTimeout(() => {
      outgoingSection.classList.add('hidden');
      incomingSection.classList.remove('hidden');
      
      // Force reflow to ensure the browser strictly separates the 'hidden' removal from the 'opacity' addition
      void incomingSection.offsetWidth; 

      // 4. Fade In Incoming
      incomingSection.classList.remove('opacity-0', 'translate-y-4');
      incomingSection.classList.add('opacity-100', 'translate-y-0');
      
      setTimeout(() => {
        isAnimating = false;
      }, 300); // Match CSS transition duration
    }, 300); // Match CSS transition duration
  }

  showCurrent.addEventListener('click', () => toggleView('current'));
  showHistory.addEventListener('click', () => toggleView('history'));
});
</script>

<style>
/* Removed .input-search CSS as we are using absolute icon now */
/* .input-search { ... } */
</style>
@endsection