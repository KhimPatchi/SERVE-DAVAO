@extends('layouts.sidebar.sidebar')

@section('title', 'Dashboard | ServeDavao')

@section('content')
<div class="space-y-8 animate-fade-in">
    
    <!-- 1. Header Section -->
    <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                Welcome back, {{ explode(' ', trim(auth()->user()->name))[0] }}! 👋
            </h1>
            <p class="text-gray-500 mt-1 flex items-center gap-2 font-medium">
                <i class="bi bi-calendar3"></i>
                <span>{{ now()->format('l, F j, Y') }}</span>
            </p>
        </div>
        
        <div class="flex items-center gap-3">
             @if(auth()->user()->isVerifiedOrganizer())
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 text-emerald-700 font-semibold border border-emerald-100 shadow-sm transition-transform hover:scale-105">
                    <i class="bi bi-patch-check-fill"></i>
                    Verified Organizer
                </span>
            @elseif(auth()->user()->hasPendingVerification())
                 <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 text-amber-700 font-semibold border border-amber-100 shadow-sm transition-transform hover:scale-105">
                    <i class="bi bi-hourglass-split"></i>
                    Verification Pending
                </span>
            @else
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-700 font-semibold border border-blue-100 shadow-sm transition-transform hover:scale-105">
                    <i class="bi bi-star-fill"></i>
                    Volunteer
                </span>
            @endif
        </div>
    </header>

    <!-- 2. Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:border-emerald-100 transition-all duration-300 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                    {{ auth()->user()->isVerifiedOrganizer() ? 'Total Volunteers' : 'Events Joined' }}
                </p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2 tracking-tight">{{ $totalVolunteers }}</h3>
                <p class="text-xs text-emerald-600 font-semibold mt-2 flex items-center gap-1">
                    <i class="bi bi-arrow-up-short text-lg"></i> Great impact!
                </p>
            </div>
            <div class="absolute right-4 top-4 p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 transform group-hover:scale-110 shadow-sm">
                <i class="bi bi-people-fill text-xl"></i>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:border-blue-100 transition-all duration-300 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Hours Given</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2 tracking-tight">{{ $totalHours }}</h3>
                 <p class="text-xs text-blue-600 font-semibold mt-2 flex items-center gap-1">
                    <i class="bi bi-clock"></i> Lifetime hours
                </p>
            </div>
             <div class="absolute right-4 top-4 p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 transform group-hover:scale-110 shadow-sm">
                <i class="bi bi-hourglass-split text-xl"></i>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:border-purple-100 transition-all duration-300 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Upcoming</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2 tracking-tight">{{ $upcomingEvents }}</h3>
                 <p class="text-xs text-purple-600 font-semibold mt-2 flex items-center gap-1">
                    <i class="bi bi-calendar-event"></i> Scheduled events
                </p>
            </div>
            <div class="absolute right-4 top-4 p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:bg-purple-600 group-hover:text-white transition-all duration-300 transform group-hover:scale-110 shadow-sm">
                <i class="bi bi-calendar-check text-xl"></i>
            </div>
        </div>

        <!-- Stat Card 4 (Level) -->
         <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 border border-emerald-500 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
             <!-- Background Texture -->
             <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E');"></div>
             
             <div class="flex items-center justify-between text-white relative z-10">
                <div>
                    <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">Current Level</p>
                     <h3 class="text-2xl font-bold mt-2 tracking-tight">
                        @if($totalHours >= 50) Champion
                        @elseif($totalHours >= 20) Leader
                        @elseif($totalHours >= 5) Supporter
                        @else Beginner
                        @endif
                    </h3>
                </div>
                <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm shadow-inner">
                    <i class="bi bi-trophy-fill text-xl"></i>
                </div>
            </div>
            <div class="mt-4 relative z-10">
                 <div class="h-1.5 bg-black/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white/90 rounded-full shadow-sm" style="width: {{ min(($totalHours / 100) * 100, 100) }}%"></div>
                </div>
                <p class="text-xs text-emerald-100 mt-2 text-right font-medium">Progress to next tier</p>
            </div>
        </div>
    </div>

    <!-- 3. Main Content & Sidebar Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Upcoming Events (Table) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">
                     {{ auth()->user()->isVerifiedOrganizer() ? 'Upcoming Organized Events' : 'Upcoming Schedule' }}
                </h2>
                <a href="{{ auth()->user()->isVerifiedOrganizer() ? route('volunteers.organized-events') : route('volunteers.my-events') }}" 
                   class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 hover:underline transition-all">
                    View All
                </a>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                @if(count($events) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-bold">
                                    <th class="px-6 py-4">Event Details</th>
                                    <th class="px-6 py-4">Date & Time</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($events as $event)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                             <div class="w-12 h-12 rounded-xl bg-gray-100 flex-shrink-0 bg-cover bg-center shadow-sm group-hover:scale-105 transition-transform duration-300"
                                                  style="background-image: url('{{ $event->image ?? asset('assets/img/event-placeholder.jpg') }}')">
                                             </div>
                                             <div>
                                                 <p class="font-bold text-gray-900 line-clamp-1 group-hover:text-emerald-600 transition-colors">{{ $event->title }}</p>
                                                 <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                                     <i class="bi bi-geo-alt text-emerald-500"></i> {{ Str::limit($event->location, 25) }}
                                                 </p>
                                             </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="text-sm font-semibold text-gray-900">{{ $event->date->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500 font-medium">{{ $event->date->format('h:i A') }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('events.show', $event) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 transition-all">
                                            <i class="bi bi-arrow-right-short text-xl"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce-slow">
                            <i class="bi bi-calendar-x text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">No events found</h3>
                        <p class="text-gray-500 text-sm mt-1 mb-6 max-w-xs mx-auto">You haven't joined any events yet. Check out what's happening nearby!</p>
                        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-all shadow-lg hover:shadow-emerald-200 hover:-translate-y-0.5">
                            Browse Events <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Quick Actions -->
        <div class="space-y-6">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Quick Actions</h2>
            
            <div class="grid grid-cols-1 gap-4">
                 @if(auth()->user()->canCreateEvents())
                    <a href="{{ route('events.create') }}" class="flex items-center p-4 bg-emerald-600 text-white rounded-2xl hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-200 hover:-translate-y-1 transition-all duration-300 group w-full text-left">
                        <div class="p-3 bg-white/20 rounded-xl mr-4 group-hover:scale-110 transition-transform">
                            <i class="bi bi-plus-lg text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold">Create Event</p>
                            <p class="text-emerald-100 text-xs opacity-90 font-medium">Host a new activity</p>
                        </div>
                    </a>
                @elseif(!auth()->user()->isVerifiedOrganizer() && !auth()->user()->hasPendingVerification())
                     <a href="{{ route('organizer.verification.create') }}" class="flex items-center p-4 bg-gray-900 text-white rounded-2xl hover:bg-black hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group w-full text-left">
                        <div class="p-3 bg-white/20 rounded-xl mr-4 group-hover:scale-110 transition-transform">
                            <i class="bi bi-patch-check text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold">Become an Organizer</p>
                            <p class="text-gray-400 text-xs font-medium">Get verified to host</p>
                        </div>
                    </a>
                @endif

                <a href="{{ route('events.index') }}" class="flex items-center p-4 bg-white border border-gray-100 rounded-2xl hover:border-emerald-200 hover:shadow-md transition-all duration-300 group w-full text-left">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl mr-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                        <i class="bi bi-search text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 group-hover:text-emerald-700 transition-colors">Browse Events</p>
                        <p class="text-gray-500 text-xs font-medium">Find opportunities nearby</p>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}" class="flex items-center p-4 bg-white border border-gray-100 rounded-2xl hover:border-purple-200 hover:shadow-md transition-all duration-300 group w-full text-left">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-xl mr-4 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                        <i class="bi bi-person-gear text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 group-hover:text-purple-700 transition-colors">Profile Settings</p>
                        <p class="text-gray-500 text-xs font-medium">Update your info</p>
                    </div>
                </a>
            </div>
            
            <!-- Tip Widget -->
            <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-2xl p-6 text-white relative overflow-hidden shadow-lg shadow-indigo-200 transition-transform hover:scale-[1.02] duration-300">
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                         <span class="px-2 py-1 bg-white/20 rounded-md text-xs font-bold uppercase tracking-wide">Daily Tip</span>
                    </div>
                    <p class="text-indigo-50 text-sm leading-relaxed font-medium">
                        "Volunteering is the ultimate exercise in democracy. You vote in elections once a year, but when you volunteer, you vote every day about the kind of community you want to live in."
                    </p>
                </div>
                <!-- Decorative circles -->
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute top-0 right-0 w-20 h-20 bg-white/5 rounded-full blur-xl"></div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Animation for Loading */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
}
</style>
@endsection