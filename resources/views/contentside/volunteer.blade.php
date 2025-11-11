    @extends('layouts.sidebar.sidebar')

    @section('content')
    <div class="p-6 bg-gray-50 min-h-screen">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Volunteers</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($volunteers as $volunteer)
                <div class="bg-white shadow-lg rounded-2xl p-6 hover:shadow-2xl transition duration-300">
                    <div class="flex items-center gap-4 mb-4">
                        {{-- Avatar picture --}}
                        <div class="w-16 h-16 rounded-full overflow-hidden ring-2 ring-blue-500">
                            <img src="{{ $volunteer->avatar ?? asset('assets/img/logoDav.png') }}" 
                                class="w-full h-full object-cover" 
                                alt="Avatar">
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $volunteer->name }}</h3>
                            <p class="text-gray-500 text-sm">{{ $volunteer->role ?? 'Volunteer' }}</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm truncate">Email: {{ $volunteer->email }}</p>
                </div>
            @empty
                <p class="text-gray-500">No volunteers found.</p>
            @endforelse
        </div>

        <p class="mt-6 text-gray-600 font-semibold">Total Volunteers: {{ $totalVolunteers }}</p>
    </div>
    @endsection
