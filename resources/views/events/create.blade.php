@extends('layouts.sidebar.sidebar')

@section('title', 'Create New Event - ServeDavao')

@section('content')
<div class="px-6 py-8 md:px-10 md:py-12 bg-gray-50/50 min-h-screen">
    
    <!-- Modern Header Section -->
    <div class="max-w-5xl mx-auto mb-10">
        <nav class="flex items-center space-x-3 text-sm font-medium text-gray-400 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-emerald-600 transition-colors">Dashboard</a>
            <i class="bi bi-chevron-right text-[10px]"></i>
            <a href="{{ route('volunteers.organized-events') }}" class="hover:text-emerald-600 transition-colors">Organized Events</a>
            <i class="bi bi-chevron-right text-[10px]"></i>
            <span class="text-emerald-600 font-bold">Create Event</span>
        </nav>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-tight mb-2">Create New Event</h1>
                <p class="text-lg text-gray-500 font-medium tracking-wide">Launch a verified volunteer opportunity</p>
            </div>
            <!-- Removed redundant 'Verified Organizer' badge from header to clean up UI -->
        </div>
    </div>

    <!-- Error/Success Trapping -->
    <div class="max-w-5xl mx-auto mb-8">
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm flex items-center justify-between animate-fade-in-down">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-x-circle-fill text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-bold">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 transition-colors">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-center justify-between animate-fade-in-down">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-check-circle-fill text-emerald-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-emerald-700 font-bold">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <div class="max-w-5xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('events.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <!-- Unified Event Configuration -->
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 md:p-10 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-teal-500"></div>
                        
                        <!-- Part 1: What & Why -->
                        <div class="mb-10">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-xl">
                                    <i class="bi bi-card-text"></i>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900">Event Details</h2>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label for="title" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">Event Title <span class="text-red-500">*</span></label>
                                    <input type="text" 
                                           id="title" 
                                           name="title" 
                                           required
                                           value="{{ old('title') }}"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-gray-300"
                                           placeholder="e.g. Coastal Cleanup Drive 2024">
                                </div>

                                <div>
                                    <label for="description" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">Description <span class="text-red-500">*</span></label>
                                    <textarea 
                                        id="description" 
                                        name="description" 
                                        rows="5"
                                        required
                                        class="w-full p-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-gray-300 leading-relaxed resize-none"
                                        placeholder="Tell volunteers what they'll be doing, why it matters, and what to expect...">{{ old('description') }}</textarea>
                                </div>

                                <!-- Skill Tags (Moved here, renamed to 'Relevant Skills' and explicit Optional) -->
                                <div>
                                    <label for="skills_required" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">
                                        Relevant Skills / Tags <span class="text-gray-400 font-normal normal-case ml-1">(Optional - helps with matching)</span>
                                    </label>
                                    <input type="text" 
                                           id="skills_required" 
                                           name="skills_required"
                                           value="{{ old('skills_required') }}"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-gray-300"
                                           placeholder="e.g. First Aid, Teaching, Driving">
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100 mb-10">

                        <!-- Part 2: Where & When (Combined Logistics) -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 text-xl">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900">Logistics</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="location" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">Detailed Location <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <i class="bi bi-pin-map-fill absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input type="text" 
                                               id="location" 
                                               name="location" 
                                               required
                                               value="{{ old('location') }}"
                                               class="w-full h-14 pl-12 pr-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-gray-300"
                                               placeholder="Full address or venue name">
                                    </div>
                                </div>

                                <div>
                                    <label for="date" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">Date <span class="text-red-500">*</span></label>
                                    <input type="date" 
                                           id="date" 
                                           name="date" 
                                           required
                                           value="{{ old('date') }}"
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all text-gray-600">
                                </div>

                                <div>
                                    <label for="time" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">Start Time <span class="text-red-500">*</span></label>
                                    <input type="time" 
                                           id="time" 
                                           name="time" 
                                           required
                                           value="{{ old('time') }}"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all text-gray-600">
                                </div>

                                <!-- Volunteers Needed -->
                                <div class="md:col-span-2">
                                    <label for="required_volunteers" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">Volunteers Needed <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" 
                                               id="required_volunteers" 
                                               name="required_volunteers" 
                                               min="1"
                                               max="1000"
                                               required
                                               value="{{ old('required_volunteers', 10) }}"
                                               class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-gray-300"
                                               placeholder="e.g. 10">
                                       
                                    </div>
                                    @error('required_volunteers')
                                        <p class="mt-2 text-sm text-red-500 font-medium flex items-center"><i class="bi bi-exclamation-circle mr-1.5"></i>{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-4">
                        <a href="{{ route('volunteers.organized-events') }}" 
                           class="px-8 h-14 flex items-center justify-center border border-gray-200 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 transition-all duration-300 font-bold tracking-wide">
                            Cancel
                        </a>
                        <button type="submit" 
                                onclick="this.disabled=true;this.innerHTML='<span class=\'spinner-border spinner-border-sm mr-2\' role=\'status\' aria-hidden=\'true\'></span>Publishing...';this.form.submit();"
                                class="px-10 h-14 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white rounded-2xl shadow-lg shadow-emerald-200 transition-all duration-300 transform hover:-translate-y-1 font-bold tracking-wide text-lg flex items-center justify-center gap-2">
                            <span>Publish Event</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column: Simple Tips -->
            <div class="lg:col-span-1">
                <div class="bg-indigo-50/50 rounded-[2.5rem] p-8 border border-indigo-50">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-500 text-xl mb-6">
                        <i class="bi bi-lightbulb-fill"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Optimization Tips</h3>
                    <ul class="space-y-4">
                        <li class="flex gap-3 items-start">
                            <i class="bi bi-check-lg text-emerald-500 font-bold mt-1"></i>
                            <span class="text-sm text-gray-600 font-medium">Use specific keywords in your title for better matching.</span>
                        </li>
                        <li class="flex gap-3 items-start">
                            <i class="bi bi-check-lg text-emerald-500 font-bold mt-1"></i>
                            <span class="text-sm text-gray-600 font-medium">Adding skill tags increases visibility to qualified volunteers.</span>
                        </li>
                        <li class="flex gap-3 items-start">
                            <i class="bi bi-check-lg text-emerald-500 font-bold mt-1"></i>
                            <span class="text-sm text-gray-600 font-medium">Set a realistic volunteer goal to encourage sign-ups.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection