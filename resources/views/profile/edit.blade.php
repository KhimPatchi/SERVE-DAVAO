@extends ('layouts.sidebar.sidebar')

@section ('title', 'Edit Profile')

@push ('head')
<link href="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css" rel="stylesheet">
<link href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" rel="stylesheet">
<style>
    #user-map { width: 100%; height: 280px; border-radius: 1rem; }
    .mapboxgl-ctrl-geocoder { width: 100%; max-width: 100%; }
</style>
@endpush

@section ('content')
<div class="p-4 md:p-6 bg-gray-50 min-h-screen">
  
  <!-- Header -->
  <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-6">
    <div class="flex-1">
      <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
        Edit Your Profile
      </h1>
      <p class="text-base text-gray-600 max-w-2xl">
        Manage your personal information, security settings, and account preferences.
      </p>
    </div>
    
    <!-- User Profile Badge -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
      <div class="flex items-center gap-4">
        <div class="relative">
          @php
            $avatarSrc = null;
            if (!empty(Auth::user()->google_avatar)) {
              $avatarSrc = Auth::user()->google_avatar;
            } elseif (!empty(Auth::user()->avatar)) {
              // Avatar is stored as 'storage/avatars/...' so just use asset() directly
              $avatarSrc = asset(Auth::user()->avatar);
            }
          @endphp
          
          @if ($avatarSrc)
            <img src="{{ $avatarSrc }}" 
                 alt="{{ Auth::user()->name }}" 
                 class="w-14 h-14 rounded-lg object-cover"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="w-14 h-14 bg-gray-800 rounded-lg flex items-center justify-center" style="display: none;">
              <span class="text-white font-semibold text-lg">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
              </span>
            </div>
          @else
            <div class="w-14 h-14 bg-gray-800 rounded-lg flex items-center justify-center">
              <span class="text-white font-semibold text-lg">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
              </span>
            </div>
          @endif
        </div>
        <div class="flex-1 min-w-0">
          <h3 class="font-semibold text-gray-900 text-base truncate">{{ Auth::user()->name }}</h3>
          <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Messages -->
  @if (session('success'))
    <div class="mb-8 bg-white border border-gray-300 text-gray-800 px-6 py-4 rounded-lg flex items-center gap-4 shadow-sm" role="alert">
      <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
        <i class="bi bi-check-circle-fill text-gray-700 text-lg"></i>
      </div>
      <div class="flex-1">
        <strong class="font-semibold">Success!</strong>
        <p class="mt-1 text-sm">{{ session('success') }}</p>
      </div>
      <button class="text-gray-500 hover:text-gray-700 transition-colors">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
  @endif

  @if ($errors->any())
    <div class="mb-8 bg-white border border-red-300 text-red-800 px-6 py-4 rounded-lg shadow-sm" role="alert">
      <div class="flex items-center gap-4">
        <div class="flex-shrink-0 w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
          <i class="bi bi-exclamation-triangle-fill text-red-600 text-lg"></i>
        </div>
        <div class="flex-1">
          <strong class="font-semibold">Please check your input:</strong>
          <ul class="mt-2 space-y-1 text-sm">
            @foreach ($errors->all() as $error)
              <li class="flex items-center gap-2">
                <i class="bi bi-dot"></i>
                {{ $error }}
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  @endif

  <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
    <!-- Navigation Sidebar -->
    <div class="xl:col-span-1 space-y-6">
      <!-- Progress Card -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
          <i class="bi bi-graph-up text-gray-600"></i>
          Profile Completion
        </h3>
        <div class="space-y-4">
          <div class="flex justify-between items-center">
            <span class="text-sm font-medium text-gray-600">Basic Info</span>
            <span class="text-sm font-semibold text-gray-900">100%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gray-800 h-2 rounded-full transition-all duration-1000" style="width: 100%"></div>
          </div>
          
          <div class="flex justify-between items-center">
            <span class="text-sm font-medium text-gray-600">Contact Details</span>
            <span class="text-sm font-semibold text-gray-900">80%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gray-800 h-2 rounded-full transition-all duration-1000" style="width: 80%"></div>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
          <i class="bi bi-activity text-gray-600"></i>
          Account Stats
        </h3>
        <div class="space-y-4">
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Member Since</span>
            <span class="font-semibold text-gray-900">{{ $user->created_at->format('M Y') }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Events Joined</span>
            <span class="font-semibold text-gray-900">12</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Hours Volunteered</span>
            <span class="font-semibold text-gray-900">48h</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="xl:col-span-3 space-y-8">
      <!-- Personal Information Card -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-5 bg-gray-50">
          <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-3">
            <i class="bi bi-person-badge text-gray-700 text-xl"></i>
            Personal Information
          </h2>
          <p class="text-gray-600 mt-1 text-sm">Update your basic profile details and contact information</p>
        </div>
        <div class="p-6">
          <form id="profile-edit-form" method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Name Field -->
              <div class="group">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                  Full Name *
                </label>
                <input id="name" type="text" 
                      class="block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 transition-all @error ('name') border-red-300 bg-red-50 @enderror" 
                      name="name" value="{{ old('name', $user->name) }}" required
                      placeholder="Enter your full name">
                @error ('name')
                  <p class="mt-2 text-sm text-red-600 flex items-center gap-2">
                    <i class="bi bi-exclamation-circle"></i>
                    {{ $message }}
                  </p>
                @enderror
              </div>

              <!-- Email Field -->
              <div class="group">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                  Email Address *
                </label>
                <input id="email" type="email" 
                      class="block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 transition-all @error ('email') border-red-300 bg-red-50 @enderror" 
                      name="email" value="{{ old('email', $user->email) }}" required
                      placeholder="Enter your email address">
                @error ('email')
                  <p class="mt-2 text-sm text-red-600 flex items-center gap-2">
                    <i class="bi bi-exclamation-circle"></i>
                    {{ $message }}
                  </p>
                @enderror
              </div>

              <!-- Phone Field -->
              <div class="group">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                  Phone Number
                </label>
                <input id="phone" type="tel" 
                      class="block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 transition-all" 
                      name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                      placeholder="+1 (555) 123-4567">
              </div>

              <!-- Location Field -->
              <div class="group">
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                  Location
                </label>
                <input id="location" type="text" 
                      class="block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 transition-all" 
                      name="location" value="{{ old('location', $user->location ?? '') }}"
                      placeholder="Your city or region">
              </div>
            </div>

            <!-- Bio Field -->
            <div class="group">
              <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                Bio
              </label>
              <textarea id="bio" name="bio" rows="4" 
                       class="block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 transition-all resize-none"
                       placeholder="Tell us about yourself, your interests, and what motivates you to volunteer...">{{ old('bio', $user->bio ?? '') }}</textarea>
              <div class="flex justify-between items-center mt-2">
                <p class="text-sm text-gray-500 flex items-center gap-1">
                  <i class="bi bi-info-circle"></i>
                  Share your story with the community
                </p>
                <span id="bioCounter" class="text-xs text-gray-400">0/500</span>
              </div>
            </div>

            <hr class="border-gray-200 my-8">

            <!-- Volunteer Preferences Section -->
            <div class="space-y-6">
              <!-- Section Header -->
              <div class="rounded-lg bg-gray-50 border border-gray-200 p-5">
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 rounded-lg bg-purple-600 flex items-center justify-center text-white text-lg flex-shrink-0 shadow-sm">
                    <i class="bi bi-stars"></i>
                  </div>
                  <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Event Preferences</h3>
                    <p class="text-sm text-gray-500 font-medium">Personalize how we match you with Davao's volunteer opportunities</p>
                  </div>
                </div>
              </div>

              <!-- Your Location (Integrated) -->
              <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                  <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                    <i class="bi bi-geo-alt-fill"></i>
                  </div>
                  <h4 class="font-bold text-gray-900">Your Base Location</h4>
                </div>

                {{-- Hidden coordinate inputs --}}
                <input type="hidden" name="latitude"  id="user_latitude"  value="{{ old('latitude', $user->latitude) }}">
                <input type="hidden" name="longitude" id="user_longitude" value="{{ old('longitude', $user->longitude) }}">

                <div id="geocoder-container" class="mb-3 rounded-xl border border-gray-100"></div>
                <div id="user-map" class="border border-gray-100 shadow-sm mb-3"></div>

                <div id="coord-badge" class="{{ $user->latitude ? '' : 'hidden' }} flex items-center gap-2 text-xs text-purple-700 font-semibold bg-purple-50 px-3 py-2 rounded-lg inline-flex">
                    <i class="bi bi-geo-alt-fill text-purple-500"></i>
                    <span id="coord-text">
                        @if ($user->latitude)
                            Stored Location: {{ number_format($user->latitude, 5) }}, {{ number_format($user->longitude, 5) }}
                        @endif
                    </span>
                </div>

                <!-- Explicit Nearby Toggle -->
                <div class="mt-4 flex items-center justify-between p-4 bg-emerald-50/50 rounded-xl border border-emerald-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <i class="bi bi-compass-fill"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Match Radius Preference</p>
                            <p class="text-xs text-gray-500">Only recommend events within <span id="radius-val" class="font-bold text-emerald-600">{{ $user->preferred_radius ?? 15 }}km</span> of your location</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <input type="range" 
                               name="preferred_radius" 
                               id="preferred_radius" 
                               min="1" 
                               max="100" 
                               step="1"
                               value="{{ old('preferred_radius', $user->preferred_radius ?? 15) }}"
                               class="w-32 h-2 bg-emerald-100 rounded-lg appearance-none cursor-pointer accent-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                               oninput="document.getElementById('radius-val').innerText = this.value + 'km'">
                        <span class="text-[10px] text-gray-400 font-medium">1km - 100km</span>
                    </div>
                </div>
              </div>

              <!-- Preferred Activities Card -->
              <div class="bg-white rounded-lg border border-gray-200 p-5">
                <div class="flex items-center gap-2 mb-4">
                  <i class="bi bi-tag-fill text-gray-700"></i>
                  <label for="preferences" class="text-base font-medium text-gray-900">
                    Preferred Activities
                  </label>
                </div>
                <!-- Single input that is directly submitted â€” no hidden field needed -->
                <div class="relative">
                  <input type="text"
                         id="preferences"
                         name="preferences"
                         placeholder="e.g., teaching, tutoring, mentoring, environment, health..."
                         value="{{ old('preferences', $user->preferences) }}"
                         class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder:text-gray-400">
                </div>
                <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                  <i class="bi bi-info-circle"></i>
                  Separate multiple activities with commas. This is used to match you with relevant events.
                </p>
              </div>

              <!-- Experience & Availability Row -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Match Priority Card - NEW -->
                <div class="col-span-full bg-white rounded-lg border border-gray-200 p-5 mb-6">
                  <div class="flex items-center gap-2 mb-4">
                    <i class="bi bi-sliders text-gray-700"></i>
                    <label class="text-base font-medium text-gray-900">
                      Recommendation Priority
                    </label>
                    <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-bold uppercase">Smart Match</span>
                  </div>
                  <p class="text-xs text-gray-500 mb-5">Choose what matters most to you. We'll adjust our recommendations accordingly.</p>
                  
                  {{-- Hidden Input for Priority --}}
                  <input type="hidden" name="primary_priority" id="primary_priority_input" value="{{ old('primary_priority', $user->primary_priority) ?? 'availability' }}">
                  
                  <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Schedule Card -->
                    <div id="card-availability" 
                         onclick="selectPriority('availability')"
                         class="priority-card h-full p-5 border-2 rounded-2xl bg-white shadow-sm transition-all duration-300 cursor-pointer relative flex flex-col items-center text-center {{ (old('primary_priority', $user->primary_priority) == 'availability' || !$user->primary_priority) ? 'border-blue-500 bg-blue-50 ring-4 ring-blue-100/50' : 'border-gray-100 hover:border-blue-200' }}">
                        <div class="check-badge absolute top-3 right-3 {{ (old('primary_priority', $user->primary_priority) == 'availability' || !$user->primary_priority) ? '' : 'hidden' }}">
                          <i class="bi bi-check-circle-fill text-blue-600 text-lg"></i>
                        </div>
                        <div class="icon-box w-12 h-12 rounded-xl flex items-center justify-center mb-3 transition-colors {{ (old('primary_priority', $user->primary_priority) == 'availability' || !$user->primary_priority) ? 'bg-blue-200 text-blue-600' : 'bg-gray-50 text-gray-400' }}">
                          <i class="bi bi-calendar-event text-2xl"></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 mb-1">Schedule</h4>
                        <p class="text-[11px] text-gray-500 leading-tight">Focuses on events that fit your available time slots.</p>
                    </div>

                    <!-- Interests Card -->
                    <div id="card-interests" 
                         onclick="selectPriority('interests')"
                         class="priority-card h-full p-5 border-2 rounded-2xl bg-white shadow-sm transition-all duration-300 cursor-pointer relative flex flex-col items-center text-center {{ old('primary_priority', $user->primary_priority) == 'interests' ? 'border-emerald-500 bg-emerald-50 ring-4 ring-emerald-100/50' : 'border-gray-100 hover:border-emerald-200' }}">
                        <div class="check-badge absolute top-3 right-3 {{ old('primary_priority', $user->primary_priority) == 'interests' ? '' : 'hidden' }}">
                          <i class="bi bi-check-circle-fill text-emerald-600 text-lg"></i>
                        </div>
                        <div class="icon-box w-12 h-12 rounded-xl flex items-center justify-center mb-3 transition-colors {{ old('primary_priority', $user->primary_priority) == 'interests' ? 'bg-emerald-200 text-emerald-600' : 'bg-gray-50 text-gray-400' }}">
                          <i class="bi bi-heart-fill text-2xl"></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 mb-1">Interests</h4>
                        <p class="text-[11px] text-gray-500 leading-tight">Prioritizes causes and activities you care about most.</p>
                    </div>

                    <!-- Location Card -->
                    <div id="card-location" 
                         onclick="selectPriority('location')"
                         class="priority-card h-full p-5 border-2 rounded-2xl bg-white shadow-sm transition-all duration-300 cursor-pointer relative flex flex-col items-center text-center {{ old('primary_priority', $user->primary_priority) == 'location' ? 'border-orange-500 bg-orange-50 ring-4 ring-orange-100/50' : 'border-gray-100 hover:border-orange-200' }}">
                        <div class="check-badge absolute top-3 right-3 {{ old('primary_priority', $user->primary_priority) == 'location' ? '' : 'hidden' }}">
                          <i class="bi bi-check-circle-fill text-orange-600 text-lg"></i>
                        </div>
                        <div class="icon-box w-12 h-12 rounded-xl flex items-center justify-center mb-3 transition-colors {{ old('primary_priority', $user->primary_priority) == 'location' ? 'bg-orange-200 text-orange-600' : 'bg-gray-50 text-gray-400' }}">
                          <i class="bi bi-geo-alt-fill text-2xl"></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 mb-1">Location</h4>
                        <p class="text-[11px] text-gray-500 leading-tight">Shows events closest to your current location first.</p>
                    </div>
                  </div>

                <!-- Experience Level Card -->
                <div class="col-span-full lg:col-span-1 bg-white rounded-lg border border-gray-200 p-5">
                  <div class="flex items-center gap-2 mb-4">
                    <i class="bi bi-trophy-fill text-gray-700"></i>
                    <label class="text-base font-medium text-gray-900">Experience Level</label>
                  </div>
                  <div class="grid grid-cols-3 gap-3">
                    <label class="relative cursor-pointer group">
                      <input type="radio" name="experience_level" value="beginner" class="peer sr-only" {{ old('experience_level', $user->experience_level) == 'beginner' ? 'checked' : '' }}>
                      <div class="h-full p-4 border-2 border-gray-100 rounded-xl peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all text-center hover:border-purple-200 shadow-sm">
                        <i class="bi bi-seedling text-2xl text-gray-400 group-hover:text-purple-400 peer-checked:text-purple-600 block mb-2"></i>
                        <p class="text-xs font-bold text-gray-900">Beginner</p>
                      </div>
                    </label>
                    <label class="relative cursor-pointer group">
                      <input type="radio" name="experience_level" value="intermediate" class="peer sr-only" {{ old('experience_level', $user->experience_level) == 'intermediate' ? 'checked' : '' }}>
                      <div class="h-full p-4 border-2 border-gray-100 rounded-xl peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all text-center hover:border-purple-200 shadow-sm">
                        <i class="bi bi-graph-up-arrow text-2xl text-gray-400 group-hover:text-purple-400 peer-checked:text-purple-600 block mb-2"></i>
                        <p class="text-xs font-bold text-gray-900">Intermediate</p>
                      </div>
                    </label>
                    <label class="relative cursor-pointer group">
                      <input type="radio" name="experience_level" value="advanced" class="peer sr-only" {{ old('experience_level', $user->experience_level) == 'advanced' ? 'checked' : '' }}>
                      <div class="h-full p-4 border-2 border-gray-100 rounded-xl peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all text-center hover:border-purple-200 shadow-sm">
                        <i class="bi bi-trophy-fill text-2xl text-gray-400 group-hover:text-purple-400 peer-checked:text-purple-600 block mb-2"></i>
                        <p class="text-xs font-bold text-gray-900">Advanced</p>
                      </div>
                    </label>
                  </div>
                </div>

                <!-- Availability Card -->
                <div class="bg-white rounded-lg border border-gray-200 p-5">
                  <div class="flex items-center gap-2 mb-4">
                    <i class="bi bi-calendar-check-fill text-gray-700"></i>
                    <label for="availability" class="text-base font-medium text-gray-900">Time & Days Available</label>
                  </div>
                  <div class="relative mb-3">
                    <i class="bi bi-clock-fill absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text"
                           name="availability" 
                           id="availability"
                           placeholder="e.g., 8:00 AM - 12:00 PM, Weekends"
                           value="{{ old('availability', $user->availability) }}"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder:text-gray-400">
                  </div>

                  <!-- Live AI Match Preview -->
                  <div id="availability-feedback" class="mb-3 empty:hidden space-y-2">
                    <!-- Dynamic badges and warnings appear here -->
                  </div>


                  <p class="mt-3 text-xs text-gray-500 flex items-center gap-1 leading-relaxed">
                    <i class="bi bi-info-circle"></i>
                    Type your specific available times and days (e.g. 8:00 AM - 12:00 PM).
                  </p>
                </div>
              </div>
            </div>

            <hr class="border-gray-200 my-8">

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
              <div class="text-sm text-gray-500 flex items-center gap-2">
                <i class="bi bi-shield-check text-gray-600"></i>
                <span>Your information is secure and encrypted</span>
              </div>
              <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <a href="{{ route('dashboard') }}" 
                   class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition-all flex items-center justify-center gap-2 order-2 sm:order-1">
                  <i class="bi bi-arrow-left"></i>
                  Back to Dashboard
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-8 rounded-lg transition-all flex items-center justify-center gap-2 order-1 sm:order-2 shadow-sm">
                  <i class="bi bi-check-lg"></i>
                  Save Changes
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

        <!-- Security Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Password Update Card -->
        @if (!$user->google_id)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
          <div class="border-b border-gray-200 px-6 py-5 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-3">
              <i class="bi bi-shield-lock text-gray-700 text-xl"></i>
              Change Password
            </h2>
            <p class="text-gray-600 mt-1 text-sm">Keep your account secure with a strong password</p>
          </div>
          <div class="p-6">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
              @csrf
              @method('PUT')

              <div class="space-y-4">
                <div class="group">
                  <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                    Current Password
                  </label>
                  <input id="current_password" type="password" 
                        class="block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 transition-all" 
                        name="current_password" 
                        placeholder="Enter current password">
                </div>

                <div class="grid grid-cols-1 gap-4">
                  <div class="group">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                      New Password
                    </label>
                    <input id="password" type="password" 
                          class="block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 transition-all" 
                          name="password" 
                          placeholder="Enter new password">
                  </div>

                  <div class="group">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                      Confirm Password
                    </label>
                    <input id="password_confirmation" type="password" 
                          class="block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 transition-all" 
                          name="password_confirmation" 
                          placeholder="Confirm new password">
                  </div>
                </div>
              </div>

              <div class="pt-4 border-t border-gray-200">
                <button type="submit" 
                        class="w-full bg-gray-800 hover:bg-gray-900 text-white font-medium py-2.5 px-6 rounded-lg transition-all flex items-center justify-center gap-2">
                  <i class="bi bi-arrow-repeat"></i>
                  Update Password
                </button>
              </div>
            </form>
          </div>
        </div>
        @endif

        <!-- Account Status Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
          <div class="border-b border-gray-200 px-6 py-5 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-3">
              <i class="bi bi-person-check text-gray-700 text-xl"></i>
              Account Status
            </h2>
            <p class="text-gray-600 mt-1 text-sm">Your account information and verification status</p>
          </div>
          <div class="p-6">
            <div class="space-y-4">
              <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-envelope-check text-gray-700"></i>
                  </div>
                  <div>
                    <span class="block text-sm font-medium text-gray-700">Email Verified</span>
                    <span class="text-xs text-gray-500">Account security</span>
                  </div>
                </div>
                @if ($user->hasVerifiedEmail())
                  <span class="bg-green-50 text-green-700 text-sm font-medium px-3 py-1 rounded-full flex items-center gap-1">
                    <i class="bi bi-check-circle-fill"></i>
                    Verified
                  </span>
                @else
                  <span class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                    <i class="bi bi-clock"></i>
                    Pending
                  </span>
                @endif
              </div>

              <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-calendar-check text-gray-700"></i>
                  </div>
                  <div>
                    <span class="block text-sm font-medium text-gray-700">Member Since</span>
                    <span class="text-xs text-gray-500">Community member</span>
                  </div>
                </div>
                <span class="text-sm font-medium text-gray-900">
                  {{ $user->created_at->format('M j, Y') }}
                </span>
              </div>

              @if ($user->isVerifiedOrganizer())
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                      <i class="bi bi-patch-check text-gray-700"></i>
                    </div>
                    <div>
                      <span class="block text-sm font-medium text-gray-700">Organizer Status</span>
                      <span class="text-xs text-gray-500">Event creation</span>
                    </div>
                  </div>
                  <span class="bg-blue-50 text-blue-700 text-sm font-medium px-3 py-1 rounded-full flex items-center gap-1">
                    <i class="bi bi-star-fill"></i>
                    Verified
                  </span>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Character counter colors */
  .text-orange-500 {
    color: #f97316;
  }
  
  .text-red-500 {
    color: #ef4444;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Bio character counter
    const bioTextarea = document.getElementById('bio');
    const bioCounter = document.getElementById('bioCounter');
    
    if (bioTextarea && bioCounter) {
      function updateBioCounter() {
        const length = bioTextarea.value.length;
        bioCounter.textContent = `${length}/500`;
        
        if (length > 450) {
          bioCounter.classList.add('text-orange-500', 'font-semibold');
        } else if (length > 500) {
          bioCounter.classList.add('text-red-500', 'font-semibold');
        } else {
          bioCounter.classList.remove('text-orange-500', 'text-red-500', 'font-semibold');
        }
      }
      
      bioTextarea.addEventListener('input', updateBioCounter);
      updateBioCounter();
    }



    // Nearby toggle logic
    const nearbyToggle = document.getElementById('nearby-toggle');
    const availabilityInput = document.getElementById('availability');

    if (nearbyToggle && availabilityInput) {
        // Initialize
        if (availabilityInput.value.toLowerCase().includes('nearby')) {
            nearbyToggle.checked = true;
        }

        nearbyToggle.addEventListener('change', function() {
            let val = availabilityInput.value.trim();
            if (this.checked) {
                if (!val.toLowerCase().includes('nearby')) {
                    availabilityInput.value = val ? (val.endsWith(',') ? `${val} nearby` : `${val}, nearby`) : 'nearby';
                }
            } else {
                // Remove 'nearby' keyword and clean up separators
                availabilityInput.value = val
                    .replace(/,?\s*nearby\s*,?/gi, ' ')
                    .trim()
                    .replace(/^,|,$/g, '')
                    .replace(/\s+/g, ' ');
            }
        });
    }

    // --- Live Availability Validation ---
    const feedbackArea = document.getElementById('availability-feedback');
    const profileForm = document.getElementById('profile-edit-form');
    const submitBtn = profileForm ? profileForm.querySelector('button[type="submit"]') : null;
    let hasAvailabilityTypo = false;

    if (availabilityInput && feedbackArea) {
        const validateAvailability = () => {
            const val = availabilityInput.value.toLowerCase();
            const recognized = [];
            const warnings = [];
            hasAvailabilityTypo = false;

            // 1. Detect Keywords
            const keywords = ['morning', 'afternoon', 'evening', 'weekend', 'weekday', 'flexible', 'anytime', 'nearby'];
            keywords.forEach(kw => {
                if (val.includes(kw)) {
                    recognized.push({ type: 'keyword', label: kw.charAt(0).toUpperCase() + kw.slice(1) });
                }
            });

            // 2. Detect Specific Times (e.g., 8:30 AM/PM)
            const timeRegex = /\b(\d{1,2})[:.]?(\d{2})?\s?(am|pm|a\.m\.|p\.m\.)?\b/g;
            let match;
            while ((match = timeRegex.exec(val)) !== null) {
                const hour = parseInt(match[1]);
                const minute = match[2] ? parseInt(match[2]) : 0;
                const period = match[3];

                if (hour > 12 && period) {
                    warnings.push(`Invalid hour: ${hour} with ${period.toUpperCase()}`);
                } else if (hour > 23 || (minute > 59)) {
                    warnings.push(`Invalid time format: ${match[0]}`);
                } else {
                    recognized.push({ type: 'time', label: match[0].toUpperCase() });
                }
            }

            // 3. Detect Typo-like patterns (e.g., 8:3p, 8;30)
            const typoRegex = /\b(\d{1,2})[:.;]([0-9]{0,1}[a-z]+)\b/gi;
            let typoMatch;
            while ((typoMatch = typoRegex.exec(val)) !== null) {
                if (!typoMatch[0].match(/(am|pm)/i)) {
                    warnings.push(`Possible typo: "${typoMatch[0]}"`);
                    hasAvailabilityTypo = true;
                }
            }

            // Render Feedback
            feedbackArea.innerHTML = '';
            
            if (recognized.length > 0) {
                const badgeWrap = document.createElement('div');
                badgeWrap.className = 'flex flex-wrap gap-1.5';
                recognized.forEach(item => {
                    const icon = item.type === 'keyword' ? 'bi-check2' : 'bi-clock-history';
                    badgeWrap.innerHTML += `
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-100 uppercase tracking-tighter">
                            <i class="bi ${icon}"></i> ${item.label}
                        </span>`;
                });
                feedbackArea.appendChild(badgeWrap);
            }

            if (warnings.length > 0) {
                warnings.forEach(warn => {
                    const warnDiv = document.createElement('div');
                    warnDiv.className = 'flex items-center gap-1.5 text-amber-600 font-bold text-[10px] bg-amber-50 rounded-md px-2 py-1 border border-amber-100 mt-1';
                    warnDiv.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> ${warn}`;
                    feedbackArea.appendChild(warnDiv);
                });
            }

            // Update Submit Button State
            if (submitBtn) {
                if (hasAvailabilityTypo) {
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-red-600');
                    submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    submitBtn.innerHTML = '<i class="bi bi-x-circle"></i> Fix errors to save';
                } else {
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-red-600');
                    submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Save Changes';
                }
            }
            
            if (recognized.length > 0 || warnings.length > 0) {
                feedbackArea.classList.remove('hidden');
            } else {
                feedbackArea.classList.add('hidden');
            }
        };

        availabilityInput.addEventListener('input', validateAvailability);
        
        // Block submission
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                if (hasAvailabilityTypo) {
                    e.preventDefault();
                    availabilityInput.focus();
                    availabilityInput.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                    setTimeout(() => availabilityInput.classList.remove('border-red-500', 'ring-1', 'ring-red-500'), 2000);
                }
            });
        }

        validateAvailability(); 
    }
  });

  // Global Choice Handler for Priority Cards
  function selectPriority(val) {
      const input = document.getElementById('primary_priority_input');
      if (!input) return;
      
      input.value = val;
      
      // Update visual states manually
      const cards = ['availability', 'interests', 'location'];
      const themes = {
          'availability': { border: 'border-blue-500', bg: 'bg-blue-50', ring: 'ring-blue-100/50', icon: 'bg-blue-200', text: 'text-blue-600' },
          'interests': { border: 'border-emerald-500', bg: 'bg-emerald-50', ring: 'ring-emerald-100/50', icon: 'bg-emerald-200', text: 'text-emerald-600' },
          'location': { border: 'border-orange-500', bg: 'bg-orange-50', ring: 'ring-orange-100/50', icon: 'bg-orange-200', text: 'text-orange-600' }
      };

      cards.forEach(c => {
          const el = document.getElementById('card-' + c);
          const badge = el.querySelector('.check-badge');
          const iconBox = el.querySelector('.icon-box');
          const theme = themes[c];

          if (c === val) {
              el.className = `priority-card h-full p-5 border-2 rounded-2xl shadow-sm transition-all duration-300 cursor-pointer relative flex flex-col items-center text-center ${theme.border} ${theme.bg} ring-4 ${theme.ring}`;
              badge.classList.remove('hidden');
              iconBox.className = `icon-box w-12 h-12 rounded-xl flex items-center justify-center mb-3 transition-colors ${theme.icon} ${theme.text}`;
          } else {
              el.className = `priority-card h-full p-5 border-2 rounded-2xl bg-white shadow-sm transition-all duration-300 cursor-pointer relative flex flex-col items-center text-center border-gray-100 hover:border-gray-200`;
              badge.classList.add('hidden');
              iconBox.className = `icon-box w-12 h-12 rounded-xl flex items-center justify-center mb-3 transition-colors bg-gray-50 text-gray-400`;
          }
      });
  }
</script>

<script src="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
<script>
(function () {
    const TOKEN = '{{ config("services.mapbox.token") }}';
    if (!TOKEN || TOKEN === 'pk.your_mapbox_public_token_here' || !document.getElementById('user-map')) return;

    mapboxgl.accessToken = TOKEN;

    const defaultCenter = [125.6131, 7.0707]; // Davao City
    const initLat = document.getElementById('user_latitude').value;
    const initLng = document.getElementById('user_longitude').value;
    const startCenter = (initLat && initLng) ? [parseFloat(initLng), parseFloat(initLat)] : defaultCenter;

    const map = new mapboxgl.Map({
        container: 'user-map',
        style: 'mapbox://styles/mapbox/light-v11',
        center: startCenter,
        zoom: (initLat && initLng) ? 14 : 11
    });

    const marker = new mapboxgl.Marker({ color: '#8b5cf6', draggable: true })
        .setLngLat(startCenter).addTo(map);

    const geocoder = new MapboxGeocoder({
        accessToken: TOKEN,
        mapboxgl: mapboxgl,
        marker: false,
        placeholder: 'Search your area/barangay...',
        bbox: [125.0, 6.5, 126.2, 7.6],
        proximity: { longitude: 125.6131, latitude: 7.0707 },
        countries: 'ph',
        types: 'place,locality,neighborhood,address',
        limit: 5
    });
    document.getElementById('geocoder-container').appendChild(geocoder.onAdd(map));

    async function reverseGeocode(lngLat) {
        try {
            const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat.lng},${lngLat.lat}.json?access_token=${TOKEN}&limit=1`;
            const response = await fetch(url);
            const data = await response.json();
            if (data.features && data.features.length > 0) return data.features[0].place_name;
        } catch (error) {}
        return null;
    }

    async function pin(lngLat) {
        document.getElementById('user_latitude').value  = lngLat.lat.toFixed(7);
        document.getElementById('user_longitude').value = lngLat.lng.toFixed(7);
        const address = await reverseGeocode(lngLat);
        const displayLabel = address ? address : (lngLat.lat.toFixed(5) + ', ' + lngLat.lng.toFixed(5));
        const coordText = document.getElementById('coord-text');
        const coordBadge = document.getElementById('coord-badge');
        if (coordText) coordText.textContent = 'Location: ' + displayLabel;
        if (coordBadge) coordBadge.classList.remove('hidden');
    }

    geocoder.on('result', (e) => {
        const [lng, lat] = e.result.geometry.coordinates;
        marker.setLngLat([lng, lat]);
        pin({ lat, lng });
    });

    map.on('click', async (e) => { 
        marker.setLngLat(e.lngLat); 
        await pin(e.lngLat); 
    });

    marker.on('dragend', async () => {
        await pin(marker.getLngLat());
    });

    // Initial pin to show stored location address
    if (initLat && initLng) {
        pin({ lat: parseFloat(initLat), lng: parseFloat(initLng) });
    }
})();
</script>
@endsection
