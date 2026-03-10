@extends ('layouts.sidebar.sidebar')

@section ('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50 p-8" id="attendance-scan">

  {{-- Breadcrumb --}}
  <nav class="mb-6 flex items-center space-x-2 text-sm text-gray-400">
    <a href="{{ route('dashboard') }}" class="hover:text-emerald-600 transition-colors font-medium">Dashboard</a>
    <span>/</span>
    <a href="{{ route('volunteers.organized-events') }}" class="hover:text-emerald-600 transition-colors font-medium">My Events</a>
    <span>/</span>
    <span class="text-gray-700 font-semibold">Scan Attendance</span>
  </nav>

  {{-- Page Header --}}
  <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Scan Attendance</h1>
      <p class="mt-1 text-gray-500">{{ $event->title }} &mdash; {{ $event->date->format('M d, Y g:i A') }}</p>
    </div>

    {{-- End Event Button --}}
    <button id="endEventBtn"
            data-event-id="{{ $event->id }}"
            class="inline-flex items-center gap-2 px-5 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-all shadow-sm hover:shadow-md disabled:opacity-50">
      <i class="bi bi-flag-fill"></i>
      End Event & Close Attendance
    </button>
  </div>

  <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

    {{-- â”€â”€ Left: QR Scanner â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

      {{-- Card Header --}}
      <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center">
          <i class="bi bi-qr-code-scan text-emerald-600 text-lg"></i>
        </div>
        <div>
          <h2 class="font-bold text-gray-900">QR Scanner</h2>
          <p class="text-xs text-gray-500">Scan via camera or upload a QR image</p>
        </div>
      </div>

      {{-- Tab Switcher --}}
      <div class="flex border-b border-gray-100">
        <button id="tabCamera"
                class="flex-1 py-3 text-sm font-semibold flex items-center justify-center gap-2 transition-colors border-b-2 border-emerald-600 text-emerald-700 bg-emerald-50/40">
          <i class="bi bi-camera-fill"></i> Camera
        </button>
        <button id="tabUpload"
                class="flex-1 py-3 text-sm font-semibold flex items-center justify-center gap-2 transition-colors border-b-2 border-transparent text-gray-500 hover:text-gray-700">
          <i class="bi bi-image-fill"></i> Upload Image
        </button>
      </div>

      <div class="p-6">
        {{-- Status Banner --}}
        <div id="scanStatus"
             class="mb-4 rounded-lg px-4 py-3 text-sm font-medium flex items-center gap-2 hidden">
          <i class="bi" id="scanStatusIcon"></i>
          <span id="scanStatusText"></span>
        </div>

        {{-- â”€â”€ Camera Panel â”€â”€ --}}
        <div id="panelCamera">
          {{-- Viewfinder wrapper â€” CSS centers html5-qrcode's injected video --}}
          <div id="qr-reader" class="qr-viewfinder w-full rounded-xl overflow-hidden bg-black"></div>
          <div class="mt-4 flex gap-3">
            <button id="startScanBtn"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors text-sm">
              <i class="bi bi-camera-fill"></i> Start Camera
            </button>
            <button id="stopScanBtn"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-300 hover:border-gray-400 text-gray-700 rounded-lg transition-colors text-sm hidden">
              <i class="bi bi-stop-circle"></i> Stop Camera
            </button>
          </div>
        </div>

        {{-- â”€â”€ Upload Panel â”€â”€ --}}
        <div id="panelUpload" class="hidden">
          {{-- Drop zone --}}
          <label for="qrImageInput"
                 id="uploadDropzone"
                 class="flex flex-col items-center justify-center gap-3 p-10 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 hover:bg-emerald-50 hover:border-emerald-400 cursor-pointer transition-all group">
            <div class="w-14 h-14 rounded-full bg-white border-2 border-gray-200 group-hover:border-emerald-400 flex items-center justify-center transition-colors shadow-sm">
              <i class="bi bi-cloud-arrow-up text-2xl text-gray-400 group-hover:text-emerald-600 transition-colors"></i>
            </div>
            <div class="text-center">
              <p class="text-sm font-semibold text-gray-700">Click or drag a QR image here</p>
              <p class="text-xs text-gray-400 mt-0.5">PNG, JPG, WebP â€” screenshot from volunteer's phone</p>
            </div>
            <input type="file" id="qrImageInput" accept="image/*" class="hidden">
          </label>

          {{-- Preview + result --}}
          <div id="uploadPreview" class="mt-4 hidden">
            <div class="flex gap-4 items-start p-4 bg-gray-50 rounded-xl border border-gray-100">
              <img id="uploadPreviewImg" src="" alt="QR preview"
                   class="w-20 h-20 object-contain rounded-lg border border-gray-200 bg-white shrink-0">
              <div class="flex-1 min-w-0">
                <p id="uploadFileName" class="text-sm font-semibold text-gray-800 truncate mb-1"></p>
                <div class="flex items-center gap-2">
                  <button id="uploadScanBtn"
                          class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition-colors">
                    <i class="bi bi-qr-code-scan"></i> Scan Now
                  </button>
                  <button id="clearUploadBtn"
                          class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 hover:bg-white text-gray-600 text-xs font-semibold rounded-lg transition-colors">
                    Change
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- â”€â”€ Right: Attendance Board â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">

      {{-- Board Header --}}
      <div class="px-6 pt-6 pb-4 border-b border-gray-100">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-sm">
              <i class="bi bi-people-fill text-white text-lg"></i>
            </div>
            <div>
              <h2 class="font-bold text-gray-900 text-base">Attendance Board</h2>
            </div>
          </div>
          {{-- Live pulse indicator --}}
          <div class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 rounded-full">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-xs font-semibold text-emerald-700">Live</span>
          </div>
        </div>

        @php
          $totalCount = $attended->count() + $registered->count();
        @endphp
        <div class="grid grid-cols-2 gap-3 mb-4">
          <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-3 text-center">
            <p class="text-2xl font-black text-emerald-700" id="attendedCount">{{ $attended->count() }}</p>
            <p class="text-[10px] font-semibold text-emerald-500 uppercase tracking-wider mt-0.5">Checked In</p>
          </div>
          <div class="rounded-xl bg-gray-50 border border-gray-100 p-3 text-center">
            <p class="text-2xl font-black text-gray-700" id="totalCount">{{ $totalCount }}</p>
            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mt-0.5">Total Registered</p>
          </div>
        </div>



        {{-- Search --}}
        <div class="relative">
          <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input id="volunteerSearch"
                 type="text"
                 placeholder="Search volunteersâ€¦"
                 class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-transparent transition-all">
        </div>
      </div>

      {{-- Volunteer List --}}
      <div class="divide-y divide-gray-50 overflow-y-auto flex-1" style="max-height: 400px" id="attendanceList">

        {{-- Attended rows --}}
        @foreach ($attended as $ev)
        @php
          $colors = ['from-emerald-400 to-teal-500','from-blue-400 to-indigo-500','from-purple-400 to-pink-500','from-orange-400 to-red-500','from-cyan-400 to-blue-500'];
          $color  = $colors[crc32($ev->volunteer->name) % count($colors)];
        @endphp
        <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50/70 transition-colors volunteer-row attended"
             data-volunteer-id="{{ $ev->volunteer->id }}"
             data-name="{{ strtolower($ev->volunteer->name) }}">
          {{-- Avatar --}}
          @if ($ev->volunteer->avatar || $ev->volunteer->google_avatar)
          <img src="{{ $ev->volunteer->avatar_url }}" class="w-10 h-10 rounded-full object-cover shrink-0 shadow-sm">
          @else
          <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $color }} flex items-center justify-center text-white text-sm font-black shrink-0 shadow-sm">
            {{ strtoupper(substr($ev->volunteer->name, 0, 1)) }}
          </div>
          @endif
          {{-- Info --}}
          <div class="flex-1 min-w-0">
            <div class="font-semibold text-gray-900 truncate text-sm">{{ $ev->volunteer->name }}</div>
            <div class="text-xs text-gray-400 truncate">{{ $ev->volunteer->email }}</div>
          </div>
          {{-- Status + time --}}
          <div class="text-right shrink-0">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
              <i class="bi bi-check2-circle"></i> Attended
            </span>
            @if ($ev->check_in_time)
            <div class="text-[10px] text-gray-400 mt-0.5 font-medium">{{ $ev->check_in_time->setTimezone(config('app.timezone'))->format('h:i A') }}</div>
            @endif
          </div>
        </div>
        @endforeach

        {{-- Pending rows --}}
        @foreach ($registered as $ev)
        @php
          $colors = ['from-emerald-400 to-teal-500','from-blue-400 to-indigo-500','from-purple-400 to-pink-500','from-orange-400 to-red-500','from-cyan-400 to-blue-500'];
          $color  = $colors[crc32($ev->volunteer->name) % count($colors)];
        @endphp
        <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50/70 transition-colors volunteer-row pending"
             data-volunteer-id="{{ $ev->volunteer->id }}"
             data-name="{{ strtolower($ev->volunteer->name) }}">
          {{-- Avatar (greyscale tint if pending) --}}
          @if ($ev->volunteer->avatar || $ev->volunteer->google_avatar)
          <img src="{{ $ev->volunteer->avatar_url }}" class="w-10 h-10 rounded-full object-cover shrink-0 grayscale-[0.4]">
          @else
          <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center text-white text-sm font-black shrink-0">
            {{ strtoupper(substr($ev->volunteer->name, 0, 1)) }}
          </div>
          @endif
          {{-- Info --}}
          <div class="flex-1 min-w-0">
            <div class="font-semibold text-gray-600 truncate text-sm">{{ $ev->volunteer->name }}</div>
            <div class="text-xs text-gray-400 truncate">{{ $ev->volunteer->email }}</div>
          </div>
          {{-- No badge here to "remove the pending" status, but keep them on list --}}
        </div>
        @endforeach

        {{-- Empty state --}}
        @if ($attended->isEmpty() && $registered->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-gray-300" id="emptyState">
          <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
            <i class="bi bi-people text-3xl text-gray-300"></i>
          </div>
          <p class="text-sm font-semibold text-gray-400">No volunteers yet</p>
          <p class="text-xs text-gray-300 mt-1">Check-ins will appear here instantly</p>
        </div>
        @endif

      </div>
    </div>
  </div>  {{-- /grid --}}
</div>  {{-- /page --}}

{{-- End Event Confirmation Modal --}}
<div id="endEventModal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
  <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 p-6">
    <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
      <i class="bi bi-flag-fill text-red-600 text-2xl"></i>
    </div>
    <h3 class="text-xl font-bold text-gray-900 text-center mb-2">End This Event?</h3>
    <p class="text-gray-500 text-center text-sm mb-6">
      This will mark all remaining <strong>pending</strong> volunteers as <strong>No-Show</strong>
      and send completion notifications to attendees. This action cannot be undone.
    </p>
    <div class="flex gap-3">
      <button id="cancelEndEvent"
              class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
        Cancel
      </button>
      <button id="confirmEndEvent"
              class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
        Yes, End Event
      </button>
    </div>
  </div>
</div>

{{-- Toast Notification --}}
<div id="toast"
     class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-4 rounded-xl shadow-2xl text-white text-sm font-semibold translate-y-20 opacity-0 transition-all duration-300 max-w-sm">
  <i class="bi text-lg" id="toastIcon"></i>
  <span id="toastMsg"></span>
</div>

{{-- â”€â”€ Scoped CSS fixes for html5-qrcode â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
<style>
/* Force the injected video to fill the wrapper and stay centered */
.qr-viewfinder { position: relative; min-height: 300px; }
.qr-viewfinder video {
  position: absolute !important;
  inset: 0 !important;
  width: 100% !important;
  height: 100% !important;
  object-fit: cover !important;
  border-radius: 0.75rem;
}
/* Center the scan box overlay */
.qr-viewfinder #qr-reader__scan_region {
  display: flex;
  align-items: center;
  justify-content: center;
}
/* Remove the default ugly border injected by the lib */
#qr-reader { border: none !important; }

/* Slide-in for newly checked-in rows */
@keyframes slideIn {
  from { opacity: 0; transform: translateX(16px); }
  to   { opacity: 1; transform: translateX(0); }
}
.animate-slide-in { animation: slideIn 0.35s ease both; }
</style>

{{-- html5-qrcode CDN --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
  'use strict';

  // â”€â”€ Config â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  const END_EVENT_URL = '{{ route("organizer.attendance.end", $event) }}';
  const CSRF = document.querySelector('meta[name=csrf-token]')?.content;

  // â”€â”€ State â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  let scanner     = null;
  let scanning    = false;
  let lastScanned = null;
  let lastScannedAt = 0;
  let activeTab   = 'camera';

  // â”€â”€ DOM refs â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  const tabCamera      = document.getElementById('tabCamera');
  const tabUpload      = document.getElementById('tabUpload');
  const panelCamera    = document.getElementById('panelCamera');
  const panelUpload    = document.getElementById('panelUpload');
  const startBtn       = document.getElementById('startScanBtn');
  const stopBtn        = document.getElementById('stopScanBtn');
  const qrImageInput   = document.getElementById('qrImageInput');
  const uploadPreview  = document.getElementById('uploadPreview');
  const uploadPreviewImg = document.getElementById('uploadPreviewImg');
  const uploadFileName = document.getElementById('uploadFileName');
  const uploadScanBtn  = document.getElementById('uploadScanBtn');
  const statusEl       = document.getElementById('scanStatus');
  const statusText     = document.getElementById('scanStatusText');
  const statusIcon     = document.getElementById('scanStatusIcon');
  const attendedCount  = document.getElementById('attendedCount');
  const attendanceList = document.getElementById('attendanceList');
  const endEventBtn    = document.getElementById('endEventBtn');
  const endModal       = document.getElementById('endEventModal');
  const cancelEnd      = document.getElementById('cancelEndEvent');
  const confirmEnd     = document.getElementById('confirmEndEvent');
  const toast          = document.getElementById('toast');
  const toastMsg       = document.getElementById('toastMsg');
  const toastIcon      = document.getElementById('toastIcon');

  // â”€â”€ Tab switching â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  async function switchTab(tab) {
    if (tab === activeTab) return;
    activeTab = tab;

    // Stop camera if switching away
    if (tab !== 'camera' && scanning && scanner) {
      await scanner.stop();
      scanner.clear();
      scanning = false;
      startBtn.classList.remove('hidden');
      stopBtn.classList.add('hidden');
    }

    if (tab === 'camera') {
      tabCamera.classList.add('border-emerald-600', 'text-emerald-700', 'bg-emerald-50/40');
      tabCamera.classList.remove('border-transparent', 'text-gray-500');
      tabUpload.classList.add('border-transparent', 'text-gray-500');
      tabUpload.classList.remove('border-emerald-600', 'text-emerald-700', 'bg-emerald-50/40');
      panelCamera.classList.remove('hidden');
      panelUpload.classList.add('hidden');
    } else {
      tabUpload.classList.add('border-emerald-600', 'text-emerald-700', 'bg-emerald-50/40');
      tabUpload.classList.remove('border-transparent', 'text-gray-500');
      tabCamera.classList.add('border-transparent', 'text-gray-500');
      tabCamera.classList.remove('border-emerald-600', 'text-emerald-700', 'bg-emerald-50/40');
      panelUpload.classList.remove('hidden');
      panelCamera.classList.add('hidden');
    }

    statusEl.classList.add('hidden');
  }

  tabCamera.addEventListener('click', () => switchTab('camera'));
  tabUpload.addEventListener('click', () => switchTab('upload'));

  // â”€â”€ Volunteer search â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  const searchInput = document.getElementById('volunteerSearch');
  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const q = this.value.toLowerCase().trim();
      document.querySelectorAll('#attendanceList .volunteer-row').forEach(row => {
        const name = (row.dataset.name || '').toLowerCase();
        row.style.display = (!q || name.includes(q)) ? '' : 'none';
      });
    });
  }

  // â”€â”€ Toast helper â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  function showToast(message, type = 'success') {
    const colors = { success:'bg-emerald-600', error:'bg-red-600', warning:'bg-amber-600', info:'bg-blue-600' };
    const icons  = { success:'bi-check-circle-fill', error:'bi-x-circle-fill', warning:'bi-exclamation-triangle-fill', info:'bi-info-circle-fill' };
    toast.className = `fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-4 rounded-xl shadow-2xl text-white text-sm font-semibold max-w-sm transition-all duration-300 ${colors[type]}`;
    toastIcon.className = `bi text-lg ${icons[type]}`;
    toastMsg.textContent = message;
    toast.classList.remove('translate-y-20', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');
    setTimeout(() => {
      toast.classList.add('translate-y-20', 'opacity-0');
      toast.classList.remove('translate-y-0', 'opacity-100');
    }, 4000);
  }

  // â”€â”€ Status banner helper â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  function setStatus(text, type = 'info') {
    const map = {
      info    : ['bg-blue-50 text-blue-700',      'bi-info-circle-fill'],
      success : ['bg-emerald-50 text-emerald-700', 'bi-check-circle-fill'],
      error   : ['bg-red-50 text-red-700',         'bi-x-circle-fill'],
      scanning: ['bg-amber-50 text-amber-700',     'bi-camera-fill'],
    };
    const [cls, icon] = map[type] ?? map.info;
    statusEl.className = `mb-4 rounded-lg px-4 py-3 text-sm font-medium flex items-center gap-2 ${cls}`;
    statusIcon.className = `bi ${icon}`;
    statusText.textContent = text;
    statusEl.classList.remove('hidden');
  }

  // â”€â”€ Attendance list update â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  // Avatar gradient colours (must match Blade list above)
  const AVATAR_COLORS = [
    'linear-gradient(135deg,#34d399,#14b8a6)',
    'linear-gradient(135deg,#60a5fa,#6366f1)',
    'linear-gradient(135deg,#c084fc,#ec4899)',
    'linear-gradient(135deg,#fb923c,#ef4444)',
    'linear-gradient(135deg,#22d3ee,#3b82f6)',
  ];
  function avatarGradient(name) {
    let h = 0;
    for (let i = 0; i < name.length; i++) h = (h * 31 + name.charCodeAt(i)) >>> 0;
    return AVATAR_COLORS[h % AVATAR_COLORS.length];
  }

  function updateProgress() {
    // We already have the total count from Blade, we don't need to recalculate it from scratch
    // unless the user wants a progress percentage (which we removed).
    // For now, this function is mostly a placeholder or can update a percentage if re-added.
  }

  function markAttended(volunteerId, volunteerName, checkedInAt, avatarUrl, hasAvatar) {
    const existing  = attendanceList.querySelector(`[data-volunteer-id="${volunteerId}"]`);
    const timeStr   = checkedInAt ?? new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    const gradient  = avatarGradient(volunteerName);

    const avatarHtml = hasAvatar 
      ? `<img src="${avatarUrl}" class="w-10 h-10 rounded-full object-cover shrink-0 shadow-sm">`
      : `<div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-black shrink-0 shadow-sm" style="background:${gradient}">
           ${volunteerName.charAt(0).toUpperCase()}
         </div>`;

    const html = `
      <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50/70 transition-colors volunteer-row attended animate-slide-in"
           data-volunteer-id="${volunteerId}" data-name="${volunteerName.toLowerCase()}">
        ${avatarHtml}
        <div class="flex-1 min-w-0">
          <div class="font-semibold text-gray-900 truncate text-sm">${volunteerName}</div>
          <div class="text-xs text-gray-400">Scanned just now</div>
        </div>
        <div class="text-right shrink-0">
          <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
            <i class="bi bi-check2-circle"></i> Attended
          </span>
          <div class="text-[10px] text-gray-400 mt-0.5 font-medium">${timeStr}</div>
        </div>
      </div>`;

    if (existing) {
      existing.outerHTML = html;
    } else {
      attendanceList.insertAdjacentHTML('afterbegin', html);
      // hide empty state if present
      const empty = document.getElementById('emptyState');
      if (empty) empty.remove();
    }

    if (!existing) {
      attendedCount.textContent = parseInt(attendedCount.textContent) + 1;
    }
    updateProgress();
  }

  // â”€â”€ Core check-in fetch â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  async function processCheckin(decodedText) {
    // Debounce â€” ignore same code within 5 s
    if (decodedText === lastScanned && Date.now() - lastScannedAt < 5000) return;
    lastScanned   = decodedText;
    lastScannedAt = Date.now();

    if (!decodedText.includes('/organizer/events/') || !decodedText.includes('/checkin/')) {
      setStatus('Invalid QR â€” not a SERVE-DAVAO ticket.', 'error');
      return;
    }

    setStatus('Processing check-inâ€¦', 'scanning');
    try {
      const res  = await fetch(decodedText, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
      });
      const data = await res.json();
      if (data.success) {
        setStatus(data.message, 'success');
        showToast(data.message, 'success');
        markAttended(data.volunteer.id, data.volunteer.name, data.checked_in_at, data.volunteer.avatar, data.volunteer.has_avatar);
      } else {
        setStatus(data.message, 'error');
        showToast(data.message, 'error');
      }
    } catch {
      setStatus('Network error â€” please try again.', 'error');
      showToast('Network error â€” please try again.', 'error');
    }
  }

  // â”€â”€ Camera scanner â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  startBtn.addEventListener('click', async () => {
    if (scanning) return;

    const container = document.getElementById('qr-reader');
    const boxSize   = Math.floor(container.offsetWidth * 0.65); // 65% of container

    scanner = new Html5Qrcode('qr-reader');
    try {
      await scanner.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: { width: boxSize, height: boxSize } },
        processCheckin,
        () => {} // silent frame errors
      );
      scanning = true;
      startBtn.classList.add('hidden');
      stopBtn.classList.remove('hidden');
      setStatus("Camera active â€” point at the volunteer's QR ticket.", 'scanning');
    } catch {
      showToast('Camera access denied or unavailable.', 'error');
    }
  });

  stopBtn.addEventListener('click', async () => {
    if (!scanning || !scanner) return;
    await scanner.stop();
    scanner.clear();
    scanning = false;
    startBtn.classList.remove('hidden');
    stopBtn.classList.add('hidden');
    statusEl.classList.add('hidden');
  });

  // â”€â”€ Image upload scanner â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  qrImageInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;

    // Show preview and hide dropzone for cleaner UI
    uploadPreviewImg.src    = URL.createObjectURL(file);
    uploadFileName.textContent = file.name;
    uploadPreview.classList.remove('hidden');
    document.getElementById('uploadDropzone').classList.add('hidden');
  });

  document.getElementById('clearUploadBtn').addEventListener('click', () => {
    qrImageInput.value = '';
    uploadPreview.classList.add('hidden');
    document.getElementById('uploadDropzone').classList.remove('hidden');
    statusEl.classList.add('hidden');
  });

  uploadScanBtn.addEventListener('click', async () => {
    const file = qrImageInput.files[0];
    if (!file) return;

    uploadScanBtn.disabled    = true;
    uploadScanBtn.textContent = 'Scanningâ€¦';
    setStatus('Reading QR code from imageâ€¦', 'scanning');

    try {
      // Use a temporary off-screen element so it doesn't hijack the camera div
      const tempId = 'qr-temp-reader';
      let tempEl   = document.getElementById(tempId);
      if (!tempEl) {
        tempEl = document.createElement('div');
        tempEl.id = tempId;
        tempEl.style.display = 'none';
        document.body.appendChild(tempEl);
      }

      const imageScanner = new Html5Qrcode(tempId);
      const result = await imageScanner.scanFile(file, /* showImage */ false);
      await imageScanner.clear();

      await processCheckin(result);
    } catch (err) {
      setStatus('No QR code found in this image. Try a clearer screenshot.', 'error');
      showToast('No QR code detected in image.', 'error');
    } finally {
      uploadScanBtn.disabled    = false;
      uploadScanBtn.innerHTML   = '<i class="bi bi-qr-code-scan"></i> Scan this image';
    }
  });

  // â”€â”€ End Event â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  endEventBtn.addEventListener('click', () => endModal.classList.remove('hidden'));
  cancelEnd.addEventListener('click',   () => endModal.classList.add('hidden'));

  confirmEnd.addEventListener('click', async () => {
    confirmEnd.disabled     = true;
    confirmEnd.textContent  = 'Endingâ€¦';
    try {
      const res  = await fetch(END_EVENT_URL, {
        method  : 'POST',
        headers : {
          'X-CSRF-TOKEN'     : CSRF,
          'X-Requested-With' : 'XMLHttpRequest',
          'Accept'           : 'application/json',
          'Content-Type'     : 'application/json',
        },
      });
      const data = await res.json();
      if (data.success) {
        endModal.classList.add('hidden');
        showToast(`Event ended. ${data.attended_count} notified, ${data.no_show_count} no-shows.`, 'success');
        endEventBtn.disabled = true;
        endEventBtn.innerHTML = 'Event Ended';
        endEventBtn.classList.replace('bg-red-600', 'bg-gray-400');
      } else {
        showToast(data.message, 'error');
      }
    } catch {
      showToast('Failed to end event â€” please try again.', 'error');
    } finally {
      confirmEnd.disabled    = false;
      confirmEnd.textContent = 'Yes, End Event';
    }
  });

})();
</script>
@endsection

