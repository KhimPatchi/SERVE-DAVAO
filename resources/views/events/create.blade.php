@extends ('layouts.sidebar.sidebar')

@section ('title', 'Create New Event - ServeDavao')

@push ('head')
<link href="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css" rel="stylesheet">
<link href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" rel="stylesheet">
<style>
    #event-map { width: 100%; height: 280px; border-radius: 1rem; }
    .mapboxgl-ctrl-geocoder { width: 100%; max-width: 100%; }
    .mapboxgl-ctrl-geocoder input { font-size: 0.875rem; }
</style>
@endpush

@section ('content')
<div class="px-6 py-8 md:px-10 md:py-12 bg-gray-50/50 min-h-screen">
    
    <!-- Modern Header Section -->
    <div class="max-w-5xl mx-auto mb-10">
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
        @if (session('error'))
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

        @if (session('success'))
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
                <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
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
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-gray-300 @error ('title') border-red-500 bg-red-50 @enderror"
                                           placeholder="e.g. Coastal Cleanup Drive 2024">
                                    @error ('title')
                                        <p class="mt-2 text-sm text-red-500 font-medium flex items-center"><i class="bi bi-exclamation-circle mr-1.5"></i>{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="description" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">Description <span class="text-red-500">*</span></label>
                                    <textarea 
                                        id="description" 
                                        name="description" 
                                        rows="5"
                                        required
                                        class="w-full p-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-gray-300 leading-relaxed resize-none @error ('description') border-red-500 bg-red-50 @enderror"
                                        placeholder="Tell volunteers what they'll be doing, why it matters, and what to expect...">{{ old('description') }}</textarea>
                                    @error ('description')
                                        <p class="mt-2 text-sm text-red-500 font-medium flex items-center"><i class="bi bi-exclamation-circle mr-1.5"></i>{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Event Image Upload - Enhanced Professional Design -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 pl-1">
                                        Event Image <span class="text-gray-400 font-normal normal-case ml-1">(Optional - helps attract volunteers)</span>
                                    </label>
                                    
                                    <!-- Upload Drop Zone - Hidden when preview is shown -->
                                    <div id="upload-zone" class="relative border-2 border-dashed border-gray-300 rounded-3xl p-8 transition-all duration-300 hover:border-emerald-400 hover:bg-emerald-50/30 group">
                                        <input type="file" 
                                               id="event_image" 
                                               name="event_image"
                                               accept="image/*"
                                               onchange="previewEventImage(event)"
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="text-center pointer-events-none">
                                            <!-- Upload Icon -->
                                            <div class="mx-auto w-16 h-16 mb-4 rounded-2xl bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                                                <i class="bi bi-cloud-arrow-up text-3xl text-emerald-600"></i>
                                            </div>
                                            
                                            <!-- Upload Text -->
                                            <div class="mb-2">
                                                <p class="text-base font-bold text-gray-700 mb-1">
                                                    <span class="text-emerald-600">Click to upload</span> or drag and drop
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    JPG, PNG or GIF (Max 5MB)
                                                </p>
                                            </div>
                                            
                                            <!-- Recommended Size -->
                                            <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 rounded-full">
                                                <i class="bi bi-info-circle text-blue-500 text-sm"></i>
                                                <span class="text-xs font-medium text-blue-700">Recommended: 1200 x 630px</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Image Preview Container - Enhanced Design -->
                                    <div id="image-preview-container" class="hidden">
                                        <div class="relative group">
                                            <!-- Preview Card -->
                                            <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 p-1 shadow-xl border border-gray-200">
                                                <div class="relative rounded-[1.4rem] overflow-hidden bg-white">
                                                    <img id="image-preview" 
                                                         src="" 
                                                         alt="Event preview" 
                                                         class="w-full h-auto max-h-80 object-cover">
                                                    
                                                    <!-- Image Overlay Info -->
                                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                        <div class="flex items-center gap-3 text-white text-sm">
                                                            <div class="flex items-center gap-1.5">
                                                                <i class="bi bi-file-image"></i>
                                                                <span id="image-filename" class="font-medium"></span>
                                                            </div>
                                                            <div class="flex items-center gap-1.5">
                                                                <i class="bi bi-hdd"></i>
                                                                <span id="image-size" class="font-medium"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Remove Button - Enhanced -->
                                            <button type="button" 
                                                    onclick="removeEventImage()" 
                                                    class="absolute -top-3 -right-3 w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center transform hover:scale-110 hover:rotate-90 z-20">
                                                <i class="bi bi-x-lg text-lg font-bold"></i>
                                            </button>
                                            
                                            <!-- Change Photo Button -->
                                            <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 z-20">
                                                <label for="event_image_change" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-50 border-2 border-gray-200 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:scale-105">
                                                    <i class="bi bi-arrow-repeat text-emerald-600"></i>
                                                    <span class="text-sm font-bold text-gray-700">Change Photo</span>
                                                </label>
                                                <input type="file" 
                                                       id="event_image_change" 
                                                       accept="image/*"
                                                       onchange="previewEventImage(event); document.getElementById('event_image').files = this.files;"
                                                       class="hidden">
                                            </div>
                                        </div>
                                        
                                        <!-- Success Badge -->
                                        <div class="mt-6 flex items-center justify-center gap-2 text-emerald-600">
                                            <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center">
                                                <i class="bi bi-check-lg text-sm font-bold"></i>
                                            </div>
                                            <span class="text-sm font-semibold">Image ready to upload</span>
                                        </div>
                                    </div>
                                    
                                    @error ('event_image')
                                        <div class="mt-3 flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-xl">
                                            <i class="bi bi-exclamation-circle text-red-500"></i>
                                            <p class="text-sm text-red-700 font-medium">{{ $message }}</p>
                                        </div>
                                    @enderror
                                </div>

                                <!-- Preferred Skills (Optional for better matching) -->
                                <div>
                                    <label for="skills_preferred" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">
                                        Event Context <span class="text-gray-400 font-normal normal-case ml-1">(Optional - helps match interested volunteers)</span>
                                    </label>
                                    <input type="text" 
                                           id="skills_preferred" 
                                           name="skills_preferred"
                                           value="{{ old('skills_preferred') }}"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-gray-300 @error ('skills_preferred') border-red-500 bg-red-50 @enderror"
                                           placeholder="e.g. First Aid, Teaching, Driving (not mandatory)">
                                    @error ('skills_preferred')
                                        <p class="mt-2 text-sm text-red-500 font-medium flex items-center"><i class="bi bi-exclamation-circle mr-1.5"></i>{{ $message }}</p>
                                    @enderror
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

                                    {{-- Hidden coordinate inputs submitted with the form --}}
                                    <input type="hidden" name="latitude"  id="event_latitude"  value="{{ old('latitude') }}">
                                    <input type="hidden" name="longitude" id="event_longitude" value="{{ old('longitude') }}">

                                    {{-- Text input (editable address label) --}}
                                    <div class="relative mb-3">
                                        <i class="bi bi-pin-map-fill absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input type="text"
                                               id="location"
                                               name="location"
                                               required
                                               value="{{ old('location') }}"
                                               class="w-full h-14 pl-12 pr-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-gray-300 @error ('location') border-red-500 bg-red-50 @enderror"
                                               placeholder="Full address or venue name"
                                               readonly>
                                    </div>

                                    {{-- Geocoder search box --}}
                                    <div id="geocoder-container" class="mb-3 rounded-xl overflow-hidden border border-gray-200"></div>
                                    <p class="text-xs text-gray-400 mb-3 pl-1"><i class="bi bi-info-circle mr-1"></i>Search for an address above, or click/drag the pin on the map to set the exact location.</p>

                                    {{-- Mapbox interactive map --}}
                                    <div id="event-map" class="border border-gray-200 shadow-sm"></div>

                                    {{-- Coordinate confirmation badge --}}
                                    <div class="flex items-center justify-between gap-4 mt-2">
                                        <div id="coord-badge" class="hidden flex items-center gap-2 text-xs text-emerald-700 font-medium">
                                            <i class="bi bi-geo-alt-fill text-emerald-500"></i>
                                            <span id="coord-text"></span>
                                        </div>
                                        
                                        <div class="flex items-center gap-3 bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-100">
                                            <label for="target_radius" class="text-xs font-bold text-emerald-800 whitespace-nowrap">Target Radius:</label>
                                            <input type="range" 
                                                   id="target_radius" 
                                                   name="target_radius" 
                                                   min="1" 
                                                   max="50" 
                                                   step="0.5" 
                                                   value="{{ old('target_radius', 15) }}"
                                                   class="w-24 h-1.5 bg-emerald-200 rounded-lg appearance-none cursor-pointer accent-emerald-600"
                                                   oninput="document.getElementById('target-radius-val').innerText = this.value + 'km'; window.updateMatchCircle(this.value);">
                                            <span id="target-radius-val" class="text-xs font-black text-emerald-700 min-w-[40px]">{{ old('target_radius', 15) }}km</span>
                                        </div>
                                    </div>

                                    @error ('location')
                                        <p class="mt-2 text-sm text-red-500 font-medium flex items-center"><i class="bi bi-exclamation-circle mr-1.5"></i>{{ $message }}</p>
                                    @enderror
                                    @error ('latitude')
                                        <p class="mt-2 text-sm text-red-500 font-medium flex items-center"><i class="bi bi-exclamation-circle mr-1.5"></i>Please pin a location on the map.</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="date" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">Date <span class="text-red-500">*</span></label>
                                    <input type="date" 
                                           id="date" 
                                           name="date" 
                                           required
                                           value="{{ old('date') }}"
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all text-gray-600 @error ('date') border-red-500 bg-red-50 @enderror">
                                    @error ('date')
                                        <p class="mt-2 text-sm text-red-500 font-medium flex items-center"><i class="bi bi-exclamation-circle mr-1.5"></i>{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="time" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">Start Time <span class="text-red-500">*</span></label>
                                    <input type="time" 
                                           id="time" 
                                           name="time" 
                                           required
                                           value="{{ old('time') }}"
                                           onchange="validateEndTime()"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all text-gray-600 @error ('time') border-red-500 bg-red-50 @enderror">
                                    @error ('time')
                                        <p class="mt-2 text-sm text-red-500 font-medium flex items-center"><i class="bi bi-exclamation-circle mr-1.5"></i>{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="end_time" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">End Time <span class="text-red-500">*</span></label>
                                    <input type="time" 
                                           id="end_time" 
                                           name="end_time" 
                                           required
                                           value="{{ old('end_time') }}"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all text-gray-600 @error ('end_time') border-red-500 bg-red-50 @enderror">
                                    @error ('end_time')
                                        <p class="mt-2 text-sm text-red-500 font-medium flex items-center"><i class="bi bi-exclamation-circle mr-1.5"></i>{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Volunteers Needed -->
                                <div class="md:col-span-2">
                                    <label for="required_volunteers" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">Volunteers Needed <span class="text-red-500">*</span></label>
                                    <input type="number" 
                                           id="required_volunteers" 
                                           name="required_volunteers" 
                                           min="1"
                                           max="1000"
                                           required
                                           value="{{ old('required_volunteers', 10) }}"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-gray-300 @error ('required_volunteers') border-red-500 bg-red-50 @enderror"
                                           placeholder="e.g. 10">
                                    @error ('required_volunteers')
                                        <p class="mt-2 text-sm text-red-500 font-medium flex items-center"><i class="bi bi-exclamation-circle mr-1.5"></i>{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-4">
                        <a href="{{ route('volunteers.organized-events') }}" 
                           class="px-8 h-14 flex items-center justify-center border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200 font-semibold text-base">
                            Cancel
                        </a>
                        <button type="submit" 
                                onclick="this.disabled=true;this.innerHTML='Publishing...';this.form.submit();"
                                class="px-8 h-14 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-sm transition-colors duration-200 font-bold text-base flex items-center justify-center min-w-[160px]">
                            Publish Event
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
                            <span class="text-sm text-gray-600 font-medium">Adding preferred skills helps find interested volunteers.</span>
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

<script>
// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Preview image function
function previewEventImage(event) {
    const file = event.target.files[0];
    const uploadZone = document.getElementById('upload-zone');
    const previewContainer = document.getElementById('image-preview-container');
    const preview = document.getElementById('image-preview');
    const filenameSpan = document.getElementById('image-filename');
    const filesizeSpan = document.getElementById('image-size');
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // Set image preview
            preview.src = e.target.result;
            
            // Set file info
            filenameSpan.textContent = file.name.length > 20 ? file.name.substring(0, 20) + '...' : file.name;
            filesizeSpan.textContent = formatFileSize(file.size);
            
            // Hide upload zone and show preview
            uploadZone.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            
            // Add animation
            setTimeout(() => {
                previewContainer.classList.add('animate-fade-in');
            }, 10);
        }
        
        reader.readAsDataURL(file);
    }
}

// Remove image function
function removeEventImage() {
    const fileInput = document.getElementById('event_image');
    const fileInputChange = document.getElementById('event_image_change');
    const uploadZone = document.getElementById('upload-zone');
    const previewContainer = document.getElementById('image-preview-container');
    const preview = document.getElementById('image-preview');
    
    // Clear the file inputs
    fileInput.value = '';
    fileInputChange.value = '';
    
    // Show upload zone and hide preview
    previewContainer.classList.add('hidden');
    uploadZone.classList.remove('hidden');
    preview.src = '';
}

// Drag and drop support
const uploadZone = document.getElementById('upload-zone');

uploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadZone.classList.add('border-emerald-500', 'bg-emerald-50');
});

uploadZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    uploadZone.classList.remove('border-emerald-500', 'bg-emerald-50');
});

uploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadZone.classList.remove('border-emerald-500', 'bg-emerald-50');
    
    const files = e.dataTransfer.files;
    if (files.length > 0 && files[0].type.startsWith('image/')) {
        document.getElementById('event_image').files = files;
        previewEventImage({ target: { files: files } });
    }
});

// Real-time time validation
function validateEndTime() {
    const startTimeInput = document.getElementById('time');
    const endTimeInput = document.getElementById('end_time');
    
    if (startTimeInput.value && endTimeInput.value) {
        if (endTimeInput.value <= startTimeInput.value) {
            // Set custom validity to prevent form submission
            endTimeInput.setCustomValidity('End time must be after start time.');
            
            // Visual feedback
            endTimeInput.classList.add('border-red-500', 'bg-red-50');
            
            // Show error message if it doesn't exist
            let errorMsg = endTimeInput.nextElementSibling;
            if (!errorMsg || !errorMsg.classList.contains('js-time-error')) {
                errorMsg = document.createElement('p');
                errorMsg.className = 'mt-2 text-sm text-red-500 font-medium flex items-center js-time-error';
                errorMsg.innerHTML = '<i class="bi bi-exclamation-circle mr-1.5"></i>End time must be after start time.';
                endTimeInput.parentNode.appendChild(errorMsg);
            }
        } else {
            // Clear validity
            endTimeInput.setCustomValidity('');
            endTimeInput.classList.remove('border-red-500', 'bg-red-50');
            
            // Remove error message
            const errorMsg = endTimeInput.parentNode.querySelector('.js-time-error');
            if (errorMsg) {
                errorMsg.remove();
            }
        }
    }
}

// Add event listeners for immediate feedback
document.getElementById('time')?.addEventListener('change', validateEndTime);
document.getElementById('end_time')?.addEventListener('change', validateEndTime);
</script>

@push ('scripts')
<script src="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
<script>
(function () {
    const TOKEN = '{{ config("services.mapbox.token") }}';
    if (!TOKEN || TOKEN === 'pk.your_mapbox_public_token_here') {
        document.getElementById('event-map').innerHTML =
            '<div style="display:flex;align-items:center;justify-content:center;height:100%;padding:1rem;color:#9ca3af;font-size:.85rem;">⚠️ Set MAPBOX_PUBLIC_TOKEN in .env to enable the map picker.</div>';
        return;
    }

    mapboxgl.accessToken = TOKEN;

    const defaultCenter = [125.6131, 7.0707]; // Davao City
    const initLat = document.getElementById('event_latitude').value;
    const initLng = document.getElementById('event_longitude').value;
    const startCenter = (initLat && initLng) ? [parseFloat(initLng), parseFloat(initLat)] : defaultCenter;

    const map = new mapboxgl.Map({
        container: 'event-map',
        style: 'mapbox://styles/mapbox/streets-v12',
        center: startCenter,
        zoom: 13
    });

    const marker = new mapboxgl.Marker({ color: '#10b981', draggable: true })
        .setLngLat(startCenter).addTo(map);

    const geocoder = new MapboxGeocoder({
        accessToken: TOKEN,
        mapboxgl: mapboxgl,
        marker: false,
        placeholder: 'Search address or venue...',
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

    async function pin(lngLat, placeName) {
        document.getElementById('event_latitude').value  = lngLat.lat.toFixed(7);
        document.getElementById('event_longitude').value = lngLat.lng.toFixed(7);
        
        let finalPlaceName = placeName;
        if (!finalPlaceName) {
            finalPlaceName = await reverseGeocode(lngLat);
        }

        if (finalPlaceName) {
            document.getElementById('location').value = finalPlaceName;
        }

        document.getElementById('coord-text').textContent =
            'Pinned: ' + lngLat.lat.toFixed(5) + ', ' + lngLat.lng.toFixed(5);
        document.getElementById('coord-badge').classList.remove('hidden');
    }

    geocoder.on('result', (e) => {
        const [lng, lat] = e.result.geometry.coordinates;
        marker.setLngLat([lng, lat]);
        pin({ lat, lng }, e.result.place_name);
        if (window.updateMatchCircle) window.updateMatchCircle();
    });

    // RADIUS CIRCLE LOGIC
    window.updateMatchCircle = function(radiusKm) {
        if (!radiusKm) radiusKm = document.getElementById('target_radius').value;
        const center = marker.getLngLat();
        
        const data = createGeoJSONCircle([center.lng, center.lat], radiusKm);
        
        if (map.getSource('polygon')) {
            map.getSource('polygon').setData(data);
        } else {
            map.addSource('polygon', {
                'type': 'geojson',
                'data': data
            });
            map.addLayer({
                'id': 'polygon',
                'type': 'fill',
                'source': 'polygon',
                'layout': {},
                'paint': {
                    'fill-color': '#10b981',
                    'fill-opacity': 0.15,
                    'fill-outline-color': '#059669'
                }
            });
        }
    };

    function createGeoJSONCircle(center, radiusInKm, points = 64) {
        const coords = {
            latitude: center[1],
            longitude: center[0]
        };
        const km = parseFloat(radiusInKm);
        const ret = [];
        const distanceX = km / (111.32 * Math.cos(coords.latitude * Math.PI / 180));
        const distanceY = km / 110.574;

        let theta, x, y;
        for (let i = 0; i < points; i++) {
            theta = (i / points) * (2 * Math.PI);
            x = distanceX * Math.cos(theta);
            y = distanceY * Math.sin(theta);
            ret.push([coords.longitude + x, coords.latitude + y]);
        }
        ret.push(ret[0]);

        return {
            'type': 'Feature',
            'geometry': {
                'type': 'Polygon',
                'coordinates': [ret]
            }
        };
    }

    map.on('style.load', () => {
        if (initLat && initLng) window.updateMatchCircle();
    });
    
    map.on('click', async (e) => { 
        marker.setLngLat(e.lngLat); 
        await pin(e.lngLat, null); 
        if (window.updateMatchCircle) window.updateMatchCircle();
    });

    marker.on('dragend', async () => {
        await pin(marker.getLngLat(), null);
        if (window.updateMatchCircle) window.updateMatchCircle();
    });

    if (initLat && initLng) {
        pin({ lat: parseFloat(initLat), lng: parseFloat(initLng) },
            document.getElementById('location').value || null);
        setTimeout(() => {
            if (window.updateMatchCircle) window.updateMatchCircle();
        }, 500);
    }
})();
</script>
@endpush

@endsection
