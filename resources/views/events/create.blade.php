@extends('layouts.sidebar.sidebar')

@section('title', 'Create New Event - ServeDavao')

@section('content')
<div class="min-h-screen bg-gray-50/60 p-6">
    
    <!-- Page Header with Breadcrumb -->
    <header class="mb-8">
        <nav class="mb-4 flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('dashboard') }}" class="transition-colors hover:text-gray-700">Dashboard</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <a href="{{ route('events.index') }}" class="transition-colors hover:text-gray-700">Events</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-gray-800 font-medium">Create Event</span>
        </nav>
        
        <div class="flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 tracking-tight">Create New Event</h1>
                    <p class="mt-3 text-xl text-gray-600 max-w-2xl">Organize a new volunteer opportunity for the community</p>
                </div>
                <div class="hidden lg:flex items-center gap-3">
                    <span class="inline-flex items-center rounded-full bg-purple-100 px-4 py-2 text-sm font-medium text-purple-800">
                        <i class="bi bi-megaphone mr-2"></i>
                        Event Organizer
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto">
        <!-- Create Event Form -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-8">
            <form action="{{ route('events.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Event Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-lg font-semibold text-gray-900 mb-3">Event Title *</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               required
                               value="{{ old('title') }}"
                               class="w-full px-4 py-4 text-lg border border-gray-300 rounded-xl focus:ring-3 focus:ring-purple-500 focus:border-transparent transition"
                               placeholder="Enter a compelling event title">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-lg font-semibold text-gray-900 mb-3">Description *</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="5"
                            required
                            class="w-full px-4 py-4 text-lg border border-gray-300 rounded-xl focus:ring-3 focus:ring-purple-500 focus:border-transparent transition"
                            placeholder="Describe your event in detail...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="md:col-span-2">
                        <label for="location" class="block text-lg font-semibold text-gray-900 mb-3">Location *</label>
                        <input type="text" 
                               id="location" 
                               name="location" 
                               required
                               value="{{ old('location') }}"
                               class="w-full px-4 py-4 text-lg border border-gray-300 rounded-xl focus:ring-3 focus:ring-purple-500 focus:border-transparent transition"
                               placeholder="Event venue or full address">
                        @error('location')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date and Time -->
                    <div>
                        <label for="date" class="block text-lg font-semibold text-gray-900 mb-3">Event Date *</label>
                        <input type="date" 
                               id="date" 
                               name="date" 
                               required
                               value="{{ old('date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-4 text-lg border border-gray-300 rounded-xl focus:ring-3 focus:ring-purple-500 focus:border-transparent transition">
                        @error('date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="time" class="block text-lg font-semibold text-gray-900 mb-3">Event Time *</label>
                        <input type="time" 
                               id="time" 
                               name="time" 
                               required
                               value="{{ old('time') }}"
                               class="w-full px-4 py-4 text-lg border border-gray-300 rounded-xl focus:ring-3 focus:ring-purple-500 focus:border-transparent transition">
                        @error('time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Required Volunteers -->
                    <div>
                        <label for="required_volunteers" class="block text-lg font-semibold text-gray-900 mb-3">Volunteers Needed *</label>
                        <input type="number" 
                               id="required_volunteers" 
                               name="required_volunteers" 
                               min="1"
                               max="1000"
                               required
                               value="{{ old('required_volunteers', 10) }}"
                               class="w-full px-4 py-4 text-lg border border-gray-300 rounded-xl focus:ring-3 focus:ring-purple-500 focus:border-transparent transition"
                               placeholder="Number of volunteers">
                        @error('required_volunteers')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Skills Required -->
                    <div>
                        <label for="skills_required" class="block text-lg font-semibold text-gray-900 mb-3">Skills Required</label>
                        <input type="text" 
                               id="skills_required" 
                               name="skills_required"
                               value="{{ old('skills_required') }}"
                               class="w-full px-4 py-4 text-lg border border-gray-300 rounded-xl focus:ring-3 focus:ring-purple-500 focus:border-transparent transition"
                               placeholder="e.g., Medical, Teaching, Construction">
                        @error('skills_required')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 mt-12 pt-8 border-t border-gray-200">
                    <a href="{{ route('volunteers.organized-events') }}" 
                       class="px-8 py-4 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors duration-200 text-lg font-semibold">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-4 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors duration-200 text-lg font-semibold shadow-lg hover:shadow-xl">
                        Create Event
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Tips -->
        <div class="mt-8 bg-blue-50 rounded-2xl p-6 border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                <i class="bi bi-lightbulb mr-2"></i>
                Tips for a Great Event
            </h3>
            <ul class="text-blue-800 space-y-2">
                <li class="flex items-center"><i class="bi bi-check-circle mr-2 text-blue-500"></i> Write a clear and compelling title</li>
                <li class="flex items-center"><i class="bi bi-check-circle mr-2 text-blue-500"></i> Provide detailed description of activities</li>
                <li class="flex items-center"><i class="bi bi-check-circle mr-2 text-blue-500"></i> Specify exact location with landmarks</li>
                <li class="flex items-center"><i class="bi bi-check-circle mr-2 text-blue-500"></i> Set realistic volunteer requirements</li>
            </ul>
        </div>
    </div>
</div>

<style>
.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
}

.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
}
</style>
@endsection