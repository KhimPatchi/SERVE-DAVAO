@extends ('layouts.sidebar.sidebar')

@section ('title', 'Edit Event - ServeDavao')

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
                <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-tight mb-2">Edit Event</h1>
                <p class="text-lg text-gray-500 font-medium tracking-wide">Update your volunteer opportunity details</p>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-3 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl font-bold transition-all shadow-sm border border-red-100">
                        <i class="bi bi-trash3"></i>
                        Delete Event
                    </button>
                </form>
            </div>
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
                <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <!-- Unified Event Configuration -->
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 md:p-10 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-400 to-indigo-500"></div>
                        
                        <!-- Part 1: What & Why -->
                        <div class="mb-10">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-xl">
                                    <i class="bi bi-pencil-square"></i>
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
                                           value="{{ old('title', $event->title) }}"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all placeholder:text-gray-300"
                                           placeholder="e.g. Coastal Cleanup Drive 2024">
                                </div>

                                <div>
                                    <label for="description" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">Description <span class="text-red-500">*</span></label>
                                    <textarea 
                                        id="description" 
                                        name="description" 
                                        rows="5"
                                        required
                                        class="w-full p-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all placeholder:text-gray-300 leading-relaxed resize-none"
                                        placeholder="Tell volunteers what they'll be doing, why it matters, and what to expect...">{{ old('description', $event->description) }}</textarea>
                                </div>

                                <!-- Event Image Upload -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 pl-1">
                                        Event Image <span class="text-gray-400 font-normal normal-case ml-1">(Optional)</span>
                                    </label>
                                    
                                    <!-- Current Image Display -->
                                    <div id="current-image-container" class="{{ $event->image ? '' : 'hidden' }} mb-4">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 pl-1">Current Image</p>
                                        <div class="relative rounded-3xl overflow-hidden border border-gray-200 shadow-sm group">
                                            @if ($event->image)
                                                <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-48 object-cover opacity-90 group-hover:opacity-100 transition-opacity">
                                            @endif
                                            <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-all"></div>
                                        </div>
                                    </div>

                                    <!-- Upload Drop Zone - Hidden when preview is shown -->
                                    <div id="upload-zone" class="relative border-2 border-dashed border-gray-300 rounded-3xl p-8 transition-all duration-300 hover:border-blue-400 hover:bg-blue-50/30 group">
                                        <input type="file" 
                                               id="event_image" 
                                               name="event_image"
                                               accept="image/*"
                                               onchange="previewEventImage(event)"
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="text-center pointer-events-none">
                                            <div class="mx-auto w-16 h-16 mb-4 rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                                                <i class="bi bi-cloud-arrow-up text-3xl text-blue-600"></i>
                                            </div>
                                            <div class="mb-2">
                                                <p class="text-base font-bold text-gray-700 mb-1">
                                                    <span class="text-blue-600">Upload new image</span> or drag it here
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Image Preview Container -->
                                    <div id="image-preview-container" class="hidden">
                                        <div class="relative group">
                                            <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 p-1 shadow-xl border border-gray-200">
                                                <div class="relative rounded-[1.4rem] overflow-hidden bg-white">
                                                    <img id="image-preview" src="" alt="Event preview" class="w-full h-auto max-h-80 object-cover">
                                                </div>
                                            </div>
                                            
                                            <button type="button" onclick="removeEventImage()" class="absolute -top-3 -right-3 w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 text-white rounded-full shadow-lg flex items-center justify-center transform hover:scale-110 z-20">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @error ('event_image')
                                        <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Preferred Skills -->
                                <div>
                                    <label for="skills_preferred" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">
                                        Event Context <span class="text-gray-400 font-normal normal-case ml-1">(Optional)</span>
                                    </label>
                                    <input type="text" 
                                           id="skills_preferred" 
                                           name="skills_preferred"
                                           value="{{ old('skills_preferred', $event->skills_preferred) }}"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all placeholder:text-gray-300"
                                           placeholder="e.g. First Aid, Teaching, Driving">
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100 mb-10">

                        <!-- Part 2: Logistics -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500 text-xl">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900">Logistics</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="location" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">Detailed Location <span class="text-red-500">*</span></label>
                                    
                                    {{-- Hidden coordinate inputs submitted with the form --}}
                                    <input type="hidden" name="latitude"  id="event_latitude"  value="{{ old('latitude', $event->latitude) }}">
                                    <input type="hidden" name="longitude" id="event_longitude" value="{{ old('longitude', $event->longitude) }}">

                                    <div class="relative mb-3">
                                        <i class="bi bi-pin-map-fill absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input type="text" 
                                               id="location" 
                                               name="location" 
                                               required
                                               value="{{ old('location', $event->location) }}"
                                               class="w-full h-14 pl-12 pr-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all placeholder:text-gray-300 @error ('location') border-red-500 bg-red-50 @enderror"
                                               placeholder="Full address or venue name"
                                               readonly>
                                    </div>

                                    {{-- Geocoder search box --}}
                                    <div id="geocoder-container" class="mb-3 rounded-xl overflow-hidden border border-gray-200"></div>
                                    <p class="text-xs text-gray-400 mb-3 pl-1"><i class="bi bi-info-circle mr-1"></i>Search for an address above, or click/drag the pin on the map to set the exact location.</p>

                                    {{-- Mapbox interactive map --}}
                                    <div id="event-map" class="border border-gray-200 shadow-sm mb-2"></div>

                                    {{-- Coordinate confirmation badge --}}
                                    <div class="flex items-center justify-between gap-4 mt-2">
                                        <div id="coord-badge" class="{{ $event->latitude ? '' : 'hidden' }} flex items-center gap-2 text-xs text-blue-700 font-medium">
                                            <i class="bi bi-geo-alt-fill text-blue-500"></i>
                                            <span id="coord-text">
                                                @if ($event->latitude)
                                                    Pinned: {{ number_format($event->latitude, 5) }}, {{ number_format($event->longitude, 5) }}
                                                @endif
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-3 bg-blue-50 px-4 py-2 rounded-xl border border-blue-100">
                                            <label for="target_radius" class="text-xs font-bold text-blue-800 whitespace-nowrap">Target Radius:</label>
                                            <input type="range" 
                                                   id="target_radius" 
                                                   name="target_radius" 
                                                   min="1" 
                                                   max="50" 
                                                   step="0.5" 
                                                   value="{{ old('target_radius', $event->target_radius ?? 15) }}"
                                                   class="w-24 h-1.5 bg-blue-200 rounded-lg appearance-none cursor-pointer accent-blue-600"
                                                   oninput="document.getElementById('target-radius-val').innerText = this.value + 'km'; window.updateMatchCircle(this.value);">
                                            <span id="target-radius-val" class="text-xs font-black text-blue-700 min-w-[40px]">{{ old('target_radius', $event->target_radius ?? 15) }}km</span>
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
                                           value="{{ old('date', $event->date->format('Y-m-d')) }}"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-gray-600">
                                </div>

                                <div>
                                    <label for="time" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">Start Time <span class="text-red-500">*</span></label>
                                    <input type="time" 
                                           id="time" 
                                           name="time" 
                                           required
                                           value="{{ old('time', $event->date->format('H:i')) }}"
                                           onchange="validateEndTime()"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-gray-600">
                                </div>

                                <div>
                                    <label for="end_time" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">End Time <span class="text-red-500">*</span></label>
                                    <input type="time" 
                                           id="end_time" 
                                           name="end_time" 
                                           required
                                           value="{{ old('end_time', $event->end_time ? $event->end_time->format('H:i') : '') }}"
                                           onchange="validateEndTime()"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-gray-600">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="required_volunteers" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5 pl-1">Volunteers Needed <span class="text-red-500">*</span></label>
                                    <input type="number" 
                                           id="required_volunteers" 
                                           name="required_volunteers" 
                                           min="1"
                                           required
                                           value="{{ old('required_volunteers', $event->required_volunteers) }}"
                                           class="w-full h-14 px-5 text-base font-medium border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all"
                                           placeholder="e.g. 10">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-4">
                        <a href="{{ route('events.show', $event) }}" 
                           class="px-8 h-14 flex items-center justify-center border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 font-semibold text-base transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-8 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all font-bold text-base flex items-center justify-center min-w-[160px]">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-1">
                <div class="bg-blue-50/50 rounded-[2.5rem] p-8 border border-blue-50">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-500 text-xl mb-6">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Editing Guide</h3>
                    <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                        Update your event information to keep volunteers informed. Changes are immediate and will be reflected on the event discovery page and volunteer dashboards.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex gap-3 items-start">
                            <i class="bi bi-check2 text-blue-500 font-bold mt-1"></i>
                            <span class="text-sm text-gray-600 font-medium tracking-tight">Updating the date or location? Make sure to inform your participants in the group chat!</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewEventImage(event) {
    const file = event.target.files[0];
    const uploadZone = document.getElementById('upload-zone');
    const previewContainer = document.getElementById('image-preview-container');
    const preview = document.getElementById('image-preview');
    const currentContainer = document.getElementById('current-image-container');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            uploadZone.classList.add('hidden');
            if (currentContainer) currentContainer.classList.add('hidden');
            previewContainer.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function removeEventImage() {
    const fileInput = document.getElementById('event_image');
    const uploadZone = document.getElementById('upload-zone');
    const previewContainer = document.getElementById('image-preview-container');
    const currentContainer = document.getElementById('current-image-container');
    
    fileInput.value = '';
    previewContainer.classList.add('hidden');
    uploadZone.classList.remove('hidden');
    if (currentContainer && currentContainer.querySelector('img')) currentContainer.classList.remove('hidden');
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
        zoom: (initLat && initLng) ? 15 : 13
    });

    const marker = new mapboxgl.Marker({ color: '#2563eb', draggable: true })
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
                    'fill-color': '#2563eb',
                    'fill-opacity': 0.1,
                    'fill-outline-color': '#1d4ed8'
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
        // Only auto-pin with label if coordinates exist
        pin({ lat: parseFloat(initLat), lng: parseFloat(initLng) },
            document.getElementById('location').value || null);
    }
})();
</script>
@endpush

@endsection
