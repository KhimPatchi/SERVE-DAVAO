@extends('layouts.sidebar.sidebar')

@section('title', 'Event Management')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Page Header -->
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Event Management</h1>
            <p class="mt-2 text-sm text-gray-600">Manage and moderate all system events.</p>
        </header>

        <!-- System Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-md bg-green-50 border border-green-200" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-check-circle-fill text-green-400 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Success</h3>
                        <div class="mt-1 text-sm text-green-700">
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Events Table -->
        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">All Events</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Organizer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Volunteers</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($events as $event)
                            @php
                                // Use the automatic current_status that considers date
                                $currentStatus = $event->current_status;
                                
                                // Check if event is in the past but still showing as active in database
                                $isPastEvent = $event->date < now() && $event->status === 'active';
                                $needsCompletion = $isPastEvent && $event->current_status === 'active';
                                
                                // Status configuration with proper colors
                                $statusConfig = [
                                    'active' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'bi-play-circle', 'text' => 'Active'],
                                    'completed' => ['class' => 'bg-blue-100 text-blue-800', 'icon' => 'bi-check-circle', 'text' => 'Completed'],
                                    'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'bi-clock', 'text' => 'Pending'],
                                    'cancelled' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'bi-x-circle', 'text' => 'Cancelled'],
                                    'rejected' => ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'bi-x-circle-fill', 'text' => 'Rejected'],
                                ];
                                
                                $config = $statusConfig[$currentStatus] ?? $statusConfig['pending'];
                            @endphp
                            
                            <tr class="hover:bg-gray-50 {{ $needsCompletion ? 'bg-orange-50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($event->description, 50) }}</div>
                                    @if($needsCompletion)
                                        <div class="text-xs text-orange-600 mt-1 flex items-center">
                                            <i class="bi bi-exclamation-triangle mr-1"></i>Past event - mark as completed
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $event->organizer->name ?? 'Unknown' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div>{{ $event->date->format('M j, Y g:i A') }}</div>
                                    <div class="text-xs {{ $event->date < now() ? 'text-red-500' : 'text-green-500' }}">
                                        @if($event->date < now())
                                            <i class="bi bi-clock-history mr-1"></i>Past event
                                        @else
                                            <i class="bi bi-clock mr-1"></i>{{ $event->date->diffForHumans() }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $event->current_volunteers }} / {{ $event->required_volunteers }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['class'] }}">
                                        <i class="bi {{ $config['icon'] }} mr-1"></i>
                                        {{ $config['text'] }}
                                    </span>
                                    @if($needsCompletion)
                                        <div class="text-xs text-orange-500 mt-1">
                                            (needs update)
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    @if($event->status === 'pending')
                                        <div class="flex space-x-3">
                                            <form action="{{ route('admin.events.approve', $event) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900 transition-colors flex items-center">
                                                    <i class="bi bi-check-lg mr-1"></i>Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.events.reject', $event) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors flex items-center">
                                                    <i class="bi bi-x-lg mr-1"></i>Reject
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($needsCompletion)
                                        <form action="{{ route('admin.events.complete', $event) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:text-blue-900 transition-colors flex items-center text-sm">
                                                <i class="bi bi-check2-all mr-1"></i>Mark Complete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500 text-sm">No action needed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="bi bi-calendar-x text-2xl mb-2 block"></i>
                                    No events found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($events->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection