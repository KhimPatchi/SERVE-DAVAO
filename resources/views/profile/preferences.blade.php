@extends ('layouts.sidebar.sidebar')

@section ('title', 'Volunteer Preferences - ServeDavao')

@push ('head')
<link href="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css" rel="stylesheet">
<link href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" rel="stylesheet">
<style>
    #user-map { width: 100%; height: 280px; border-radius: 1rem; }
    .mapboxgl-ctrl-geocoder {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 100% !important;
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 0.75rem !important;
        float: none !important;
        margin: 0 0 0.75rem 0 !important;
        position: relative !important;
        box-sizing: border-box !important;
    }
    .mapboxgl-ctrl-geocoder--input {
        padding-left: 36px !important;
        padding-right: 36px !important;
        height: 44px !important;
        font-size: 0.875rem !important;
        width: 100% !important;
        box-sizing: border-box !important;
        border-radius: 0.75rem !important;
    }
    .mapboxgl-ctrl-geocoder--icon-search {
        top: 12px !important;
        left: 10px !important;
        position: absolute !important;
    }
</style>
@endpush

@section ('content')
<div class="px-6 py-8 md:px-10 md:py-12 bg-gray-50/50 min-h-screen">
    
    <!-- Header -->
    <div class="max-w-4xl mx-auto mb-10">
        <nav class="flex items-center space-x-3 text-sm font-medium text-gray-400 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-purple-600 transition-colors">Dashboard</a>
            <i class="bi bi-chevron-right text-[10px]"></i>
            <a href="{{ route('profile.edit') }}" class="hover:text-purple-600 transition-colors">Profile</a>
            <i class="bi bi-chevron-right text-[10px]"></i>
            <span class="text-purple-600 font-bold">My Preferences</span>
        </nav>

        <div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-tight mb-2">My Volunteer Preferences</h1>
            <p class="text-lg text-gray-500 font-medium">Help us match you with events you'll love</p>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="max-w-4xl mx-auto mb-8">
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm flex items-center justify-between animate-fade-in-down">
                <div class="flex items-center">
                    <i class="bi bi-check-circle-fill text-green-500 text-xl"></i>
                    <p class="ml-3 text-sm text-green-700 font-bold">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Form -->
    <form action="{{ route('profile.preferences.update') }}" method="POST" class="max-w-4xl mx-auto">
        @csrf
        
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 md:p-10 relative overflow-hidden mb-8">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-purple-400 via-fuchsia-500 to-blue-500"></div>
            
            <!-- Preferred Activities -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 text-xl">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Preferred Activities</h2>
                        <p class="text-sm text-gray-500">Select activities you enjoy or would like to try</p>
                    </div>
                </div>

                <!-- Dynamic Tag Cloud -->
                <div class="mb-4">
                    <div id="tag-cloud" class="flex flex-wrap gap-2">
                        @foreach($popularTags as $tag)
                            <button type="button"
                                class="tag-button px-4 py-2 rounded-full border-2 border-gray-200 bg-white hover:border-purple-500 hover:bg-purple-50 transition-all duration-200 text-sm font-medium text-gray-700 hover:text-purple-700 relative group"
                                data-tag="{{ $tag['name'] }}"
                                style="font-size: {{ min(1 + ($tag['popularity'] / 100), 1.4) }}rem;">
                                {{ $tag['name'] }}
                                <span class="ml-1.5 text-xs text-gray-400 group-hover:text-purple-400">{{ $tag['event_count'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Hidden input to store selected tags -->
                <input type="hidden" name="preferences" id="preferences-input" value="{{ old('preferences', $user->preferences) }}">
                
                <!-- Custom tag input -->
                <div class="mt-4">
                    <input type="text" 
                           id="custom-tag-input"
                           placeholder="Add your own interests (comma-separated)..."
                           class="w-full h-12 px-4 text-sm border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-200 focus:border-purple-500"
                           value="{{ old('preferences', $user->preferences) }}">
                    <p class="mt-2 text-xs text-gray-500">Click tags above or type your own, separated by commas</p>
                </div>

                @if($suggestedPreferences->isNotEmpty())
                    <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <p class="text-sm font-bold text-blue-900 mb-2">
                            <i class="bi bi-lightbulb-fill mr-1"></i> Suggested based on your activity:
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($suggestedPreferences as $suggestion)
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                    {{ $suggestion }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

           <hr class="border-gray-100 mb-10">

            <!-- General Interests -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-fuchsia-50 flex items-center justify-center text-fuchsia-600 text-xl">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Your Interests</h2>
                        <p class="text-sm text-gray-500">Tell us what causes and communities you care about</p>
                    </div>
                </div>

                <textarea name="interests" 
                          rows="4"
                          class="w-full p-4 text-base border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-200 focus:border-purple-500 placeholder:text-gray-300"
                          placeholder="e.g. I'm passionate about education, children's welfare, and environmental conservation...">{{ old('interests', $user->interests) }}</textarea>
            </div>

            <hr class="border-gray-100 mb-10">

            <!-- Experience Level -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-xl">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Experience Level</h2>
                        <p class="text-sm text-gray-500">How would you describe your volunteering experience?</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="relative">
                        <input type="radio" name="experience_level" value="beginner" 
                               class="peer hidden" 
                               {{ old('experience_level', $user->experience_level) == 'beginner' ? 'checked' : '' }}>
                        <div class="p-6 border-2 border-gray-200 rounded-2xl cursor-pointer peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all">
                            <div class="text-center">
                                <i class="bi bi-seedling text-3xl text-gray-400 peer-checked:text-purple-600"></i>
                                <p class="mt-2 font-bold text-gray-900">Beginner</p>
                                <p class="text-xs text-gray-500 mt-1">New to volunteering</p>
                            </div>
                        </div>
                    </label>

                    <label class="relative">
                        <input type="radio" name="experience_level" value="intermediate" 
                               class="peer hidden"
                               {{ old('experience_level', $user->experience_level) == 'intermediate' ? 'checked' : '' }}>
                        <div class="p-6 border-2 border-gray-200 rounded-2xl cursor-pointer peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all">
                            <div class="text-center">
                                <i class="bi bi-graph-up-arrow text-3xl text-gray-400 peer-checked:text-purple-600"></i>
                                <p class="mt-2 font-bold text-gray-900">Intermediate</p>
                                <p class="text-xs text-gray-500 mt-1">Some experience</p>
                            </div>
                        </div>
                    </label>

                    <label class="relative">
                        <input type="radio" name="experience_level" value="advanced" 
                               class="peer hidden"
                               {{ old('experience_level', $user->experience_level) == 'advanced' ? 'checked' : '' }}>
                        <div class="p-6 border-2 border-gray-200 rounded-2xl cursor-pointer peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all">
                            <div class="text-center">
                                <i class="bi bi-trophy-fill text-3xl text-gray-400 peer-checked:text-purple-600"></i>
                                <p class="mt-2 font-bold text-gray-900">Advanced</p>
                                <p class="text-xs text-gray-500 mt-1">Experienced volunteer</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <hr class="border-gray-100 mb-10">

            <!-- Availability -->
            <div x-data="availabilityPicker('{{ old('availability', $user->availability) }}')" x-init="init()">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-xl">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">When Are You Available?</h2>
                        <p class="text-sm text-gray-500">Pick your days and time window</p>
                    </div>
                </div>

                {{-- Hidden input that stores the composed string for the backend --}}
                <input type="hidden" name="availability" :value="composed">

                {{-- Day Toggles --}}
                <div class="mb-6">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Days</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="day in days" :key="day.short">
                            <button type="button"
                                    @click="toggleDay(day.short)"
                                    :class="selectedDays.includes(day.short)
                                        ? 'bg-emerald-500 border-emerald-500 text-white shadow-md shadow-emerald-100'
                                        : 'bg-white border-gray-200 text-gray-600 hover:border-emerald-400 hover:text-emerald-600'"
                                    class="px-4 py-2 rounded-full border-2 text-sm font-semibold transition-all duration-200 select-none">
                                <span x-text="day.label"></span>
                            </button>
                        </template>
                    </div>
                    {{-- Quick presets --}}
                    <div class="flex flex-wrap gap-2 mt-3">
                        <button type="button" @click="applyPreset('weekdays')"
                                class="text-xs px-3 py-1 rounded-full border border-dashed border-gray-300 text-gray-500 hover:border-emerald-400 hover:text-emerald-600 transition-all">
                            ⚡ Weekdays
                        </button>
                        <button type="button" @click="applyPreset('weekends')"
                                class="text-xs px-3 py-1 rounded-full border border-dashed border-gray-300 text-gray-500 hover:border-emerald-400 hover:text-emerald-600 transition-all">
                            🌅 Weekends
                        </button>
                        <button type="button" @click="applyPreset('everyday')"
                                class="text-xs px-3 py-1 rounded-full border border-dashed border-gray-300 text-gray-500 hover:border-emerald-400 hover:text-emerald-600 transition-all">
                            📅 Everyday
                        </button>
                        <button type="button" @click="applyPreset('clear')"
                                class="text-xs px-3 py-1 rounded-full border border-dashed border-red-200 text-red-400 hover:border-red-400 hover:text-red-500 transition-all">
                            ✕ Clear
                        </button>
                    </div>
                </div>

                {{-- Time Range --}}
                <div class="mb-5">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Time Window</p>
                    <div class="grid grid-cols-2 gap-4">
                        {{-- From --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">From</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-emerald-500 pointer-events-none">
                                    <i class="bi bi-clock text-sm"></i>
                                </span>
                                <select x-model="fromTime"
                                        @change="compose()"
                                        class="w-full h-11 pl-9 pr-4 text-sm border border-gray-200 rounded-xl bg-white focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 appearance-none cursor-pointer text-gray-700 font-medium">
                                    <option value="">Any time</option>
                                    <template x-for="t in timeSlots" :key="t.value">
                                        <option :value="t.value" x-text="t.label"></option>
                                    </template>
                                </select>
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs">▼</span>
                            </div>
                        </div>

                        {{-- To --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">To</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-emerald-500 pointer-events-none">
                                    <i class="bi bi-clock-fill text-sm"></i>
                                </span>
                                <select x-model="toTime"
                                        @change="compose()"
                                        class="w-full h-11 pl-9 pr-4 text-sm border border-gray-200 rounded-xl bg-white focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 appearance-none cursor-pointer text-gray-700 font-medium">
                                    <option value="">Any time</option>
                                    <template x-for="t in timeSlots" :key="t.value">
                                        <option :value="t.value" x-text="t.label"></option>
                                    </template>
                                </select>
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs">▼</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Live preview badge --}}
                <div x-show="composed" x-transition
                     class="flex items-center gap-2 px-4 py-2.5 bg-emerald-50 border border-emerald-100 rounded-xl text-sm text-emerald-700 font-medium">
                    <i class="bi bi-check-circle-fill text-emerald-500"></i>
                    <span x-text="composed"></span>
                </div>
                <div x-show="!composed"
                     class="flex items-center gap-2 px-4 py-2.5 bg-gray-50 border border-dashed border-gray-200 rounded-xl text-sm text-gray-400">
                    <i class="bi bi-info-circle"></i>
                    Select days and/or a time window above
                </div>
            </div>

            <hr class="border-gray-100 my-10">

            <!-- Your Location -->
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-xl">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Your Base Location</h2>
                        <p class="text-sm text-gray-500">Pin your location to find "nearby" volunteer events</p>
                    </div>
                </div>

                {{-- Hidden coordinate inputs --}}
                <input type="hidden" name="latitude"  id="user_latitude"  value="{{ old('latitude', $user->latitude) }}">
                <input type="hidden" name="longitude" id="user_longitude" value="{{ old('longitude', $user->longitude) }}">

                <div id="geocoder-container" class="mb-3 rounded-xl overflow-hidden border border-gray-100"></div>
                <div id="user-map" class="border border-gray-100 shadow-sm mb-3"></div>

                <div id="coord-badge" class="{{ $user->latitude ? '' : 'hidden' }} flex items-center gap-2 text-xs text-purple-700 font-medium">
                    <i class="bi bi-geo-alt-fill text-purple-500"></i>
                    <span id="coord-text">
                        @if($user->latitude)
                            Stored Location: {{ number_format($user->latitude, 5) }}, {{ number_format($user->longitude, 5) }}
                        @endif
                    </span>
                </div>
            </div>
            <hr class="border-gray-100 my-10">

            <!-- Primary Priority -->
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 text-xl">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">What is your primary priority when looking for events?</h2>
                        <p class="text-sm text-gray-500">Pick the main factor we should use to recommend events</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="relative">
                        <input type="radio" name="primary_priority" value="availability" 
                               class="peer hidden" 
                               {{ old('primary_priority', $user->primary_priority ?? 'availability') == 'availability' ? 'checked' : '' }}>
                        <div class="p-6 border-2 border-gray-200 rounded-2xl cursor-pointer peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all text-center">
                            <i class="bi bi-calendar2-check text-3xl text-gray-400 peer-checked:text-orange-600"></i>
                            <p class="mt-2 font-bold text-gray-900">My Schedule</p>
                            <p class="text-xs text-gray-500 mt-1">Focus on Availability</p>
                        </div>
                    </label>

                    <label class="relative">
                        <input type="radio" name="primary_priority" value="interests" 
                               class="peer hidden"
                               {{ old('primary_priority', $user->primary_priority ?? 'availability') == 'interests' ? 'checked' : '' }}>
                        <div class="p-6 border-2 border-gray-200 rounded-2xl cursor-pointer peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all text-center">
                            <i class="bi bi-heart text-3xl text-gray-400 peer-checked:text-orange-600"></i>
                            <p class="mt-2 font-bold text-gray-900">My Skills & Interests</p>
                            <p class="text-xs text-gray-500 mt-1">Focus on Match Quality</p>
                        </div>
                    </label>

                    <label class="relative">
                        <input type="radio" name="primary_priority" value="location" 
                               class="peer hidden"
                               {{ old('primary_priority', $user->primary_priority ?? 'availability') == 'location' ? 'checked' : '' }}>
                        <div class="p-6 border-2 border-gray-200 rounded-2xl cursor-pointer peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all text-center">
                            <i class="bi bi-geo-alt text-3xl text-gray-400 peer-checked:text-orange-600"></i>
                            <p class="mt-2 font-bold text-gray-900">Event Location</p>
                            <p class="text-xs text-gray-500 mt-1">Focus on Proximity</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center">
            <a href="{{ route('profile.edit') }}" 
               class="px-6 h-12 flex items-center border border-gray-200 text-gray-500 rounded-xl hover:bg-gray-50 transition-all font-medium">
                Cancel
            </a>
            <button type="submit" 
                    class="px-8 h-12 bg-gradient-to-r from-purple-600 via-fuchsia-500 to-blue-600 hover:from-purple-700 hover:via-fuchsia-600 hover:to-blue-700 text-white rounded-xl shadow-lg shadow-purple-200 transition-all duration-300 transform hover:-translate-y-0.5 font-bold flex items-center gap-2">
                <i class="bi bi-check-circle"></i>
                Save Preferences
            </button>
        </div>
    </form>
</div>

@push ('scripts')
<script>
    // Interactive tag cloud functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tagButtons = document.querySelectorAll('.tag-button');
        const hiddenInput = document.getElementById('preferences-input');
        const customInput = document.getElementById('custom-tag-input');
        
        // Initialize selected tags from existing preferences
        let selectedTags = new Set();
        if (hiddenInput.value) {
            hiddenInput.value.split(',').forEach(tag => selectedTags.add(tag.trim()));
        }
        
        // Mark already selected tags as active
        tagButtons.forEach(button => {
            if (selectedTags.has(button.dataset.tag)) {
                button.classList.add('border-purple-500', 'bg-purple-100', 'text-purple-700');
                button.classList.remove('border-gray-200', 'bg-white');
            }
        });

        // Tag button click handler
        tagButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tag = this.dataset.tag;
                
                if (selectedTags.has(tag)) {
                    selectedTags.delete(tag);
                    this.classList.remove('border-purple-500', 'bg-purple-100', 'text-purple-700');
                    this.classList.add('border-gray-200', 'bg-white');
                } else {
                    selectedTags.add(tag);
                    this.classList.add('border-purple-500', 'bg-purple-100', 'text-purple-700');
                    this.classList.remove('border-gray-200', 'bg-white');
                }
                
                updateInputs();
            });
        });

        // Custom input handler
        customInput.addEventListener('input', function() {
            const customTags = this.value.split(',').map(tag => tag.trim()).filter(tag => tag);
            selectedTags = new Set(customTags);
            updateInputs();
        });

        function updateInputs() {
            const tagsArray = Array.from(selectedTags);
            hiddenInput.value = tagsArray.join(', ');
            customInput.value = tagsArray.join(', ');
        }
    });

    // ── Availability Picker (Alpine.js component) ────────────────────────────
    function availabilityPicker(savedValue) {
        return {
            // ── State ──────────────────────────────────────────
            days: [
                { short: 'Mon', label: 'Mon' },
                { short: 'Tue', label: 'Tue' },
                { short: 'Wed', label: 'Wed' },
                { short: 'Thu', label: 'Thu' },
                { short: 'Fri', label: 'Fri' },
                { short: 'Sat', label: 'Sat' },
                { short: 'Sun', label: 'Sun' },
            ],
            selectedDays: [],
            fromTime: '',
            toTime: '',
            composed: '',
            timeSlots: [],

            // ── Init ───────────────────────────────────────────
            init() {
                this.buildTimeSlots();
                this.parseExisting(savedValue || '');
                this.compose();
            },

            // Build 30-min time slots from 12:00 AM to 11:30 PM
            buildTimeSlots() {
                const slots = [];
                for (let h = 0; h < 24; h++) {
                    for (let m of [0, 30]) {
                        const hour12 = h === 0 ? 12 : h > 12 ? h - 12 : h;
                        const ampm   = h < 12 ? 'AM' : 'PM';
                        const hh     = String(h).padStart(2, '0');
                        const mm     = String(m).padStart(2, '0');
                        const label  = `${hour12}:${mm === '0' ? '00' : mm} ${ampm}`;
                        slots.push({ value: `${hh}:${mm}`, label });
                    }
                }
                this.timeSlots = slots;
            },

            // Parse an existing saved string like "Mon, Wed | 8:00 AM – 5:00 PM"
            // into selectedDays, fromTime, toTime
            parseExisting(val) {
                if (!val) return;

                // Split by "|" or "–" or "-" separating days from time range
                const pipeIdx = val.indexOf('|');
                let daysPart  = pipeIdx !== -1 ? val.slice(0, pipeIdx).trim() : val.trim();
                let timePart  = pipeIdx !== -1 ? val.slice(pipeIdx + 1).trim() : '';

                // Restore selected days
                const dayNames = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
                dayNames.forEach(d => {
                    if (daysPart.includes(d)) this.selectedDays.push(d);
                });

                // Restore time range
                if (timePart) {
                    // Normalize "8:00 AM – 5:00 PM" or "8:00 AM - 5:00 PM"
                    const parts = timePart.split(/–|-/).map(s => s.trim());
                    if (parts[0]) this.fromTime = this.labelToValue(parts[0]);
                    if (parts[1]) this.toTime   = this.labelToValue(parts[1]);
                }
            },

            // Convert "8:00 AM" → "08:00" for select matching
            labelToValue(label) {
                const found = this.timeSlots.find(t => t.label.toLowerCase() === label.toLowerCase());
                return found ? found.value : '';
            },

            // ── Day toggling ───────────────────────────────────
            toggleDay(short) {
                const idx = this.selectedDays.indexOf(short);
                if (idx === -1) this.selectedDays.push(short);
                else            this.selectedDays.splice(idx, 1);
                // Keep ordered Mon→Sun
                const order = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
                this.selectedDays.sort((a,b) => order.indexOf(a) - order.indexOf(b));
                this.compose();
            },

            // ── Presets ────────────────────────────────────────
            applyPreset(preset) {
                if (preset === 'weekdays')  this.selectedDays = ['Mon','Tue','Wed','Thu','Fri'];
                if (preset === 'weekends')  this.selectedDays = ['Sat','Sun'];
                if (preset === 'everyday')  this.selectedDays = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
                if (preset === 'clear') {
                    this.selectedDays = [];
                    this.fromTime = '';
                    this.toTime   = '';
                }
                this.compose();
            },

            // ── Compose the final string ───────────────────────
            compose() {
                const dayStr  = this.selectedDays.join(', ');
                const fromLbl = this.fromTime ? this.timeSlots.find(t => t.value === this.fromTime)?.label : '';
                const toLbl   = this.toTime   ? this.timeSlots.find(t => t.value === this.toTime)?.label   : '';

                let result = dayStr;
                if (fromLbl || toLbl) {
                    const timeRange = [fromLbl, toLbl].filter(Boolean).join(' – ');
                    result = result ? `${result} | ${timeRange}` : timeRange;
                }
                this.composed = result;
            },
        };
    }
</script>

<script src="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
<script>
(function () {
    const TOKEN = '{{ config("services.mapbox.token") }}';
    if (!TOKEN || TOKEN === 'pk.your_mapbox_public_token_here') {
        document.getElementById('user-map').innerHTML =
            '<div style="display:flex;align-items:center;justify-content:center;height:100%;padding:1rem;color:#9ca3af;font-size:.85rem;">⚠️ Set MAPBOX_PUBLIC_TOKEN in .env for nearby detection.</div>';
        return;
    }

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
        proximity: { longitude: 125.6131, latitude: 7.0707 }
    });
    document.getElementById('geocoder-container').appendChild(geocoder.onAdd(map));

    async function reverseGeocode(lngLat) {
        try {
            const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat.lng},${lngLat.lat}.json?access_token=${TOKEN}&limit=1`;
            const response = await fetch(url);
            const data = await response.json();
            if (data.features && data.features.length > 0) {
                return data.features[0].place_name;
            }
        } catch (error) {
            console.error('Reverse geocoding error:', error);
        }
        return null;
    }

    async function pin(lngLat) {
        document.getElementById('user_latitude').value  = lngLat.lat.toFixed(7);
        document.getElementById('user_longitude').value = lngLat.lng.toFixed(7);
        
        const address = await reverseGeocode(lngLat);
        const displayLabel = address ? address : (lngLat.lat.toFixed(5) + ', ' + lngLat.lng.toFixed(5));

        document.getElementById('coord-text').textContent = 'Location: ' + displayLabel;
        document.getElementById('coord-badge').classList.remove('hidden');
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

    // Show initial pin and address if coordinates exist
    if (initLat && initLng) {
        pin({ lat: parseFloat(initLat), lng: parseFloat(initLng) });
    }
})();
</script>
@endpush
@endsection

