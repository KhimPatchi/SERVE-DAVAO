@extends('layouts.sidebar.sidebar')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Organizer Verification Audit Logs</h1>
        <p class="text-gray-600 mt-2">Track all organizer approval and rejection activities</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Logs</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalLogs) }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="bi bi-journal-text text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Today's Logs</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $todayLogs }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="bi bi-clock text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Approvals</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $organizerApprovals }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="bi bi-person-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Rejections</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $organizerRejections }}</h3>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                    <i class="bi bi-person-x text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.audit.logs') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" placeholder="Search by user, organization, or description..." 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <select name="action" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="all">All Actions</option>
                <option value="organizer_approved" {{ request('action') == 'organizer_approved' ? 'selected' : '' }}>Approved</option>
                <option value="organizer_rejected" {{ request('action') == 'organizer_rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="user_role_updated" {{ request('action') == 'user_role_updated' ? 'selected' : '' }}>Role Changes</option>
                <option value="event_approved" {{ request('action') == 'event_approved' ? 'selected' : '' }}>Event Approved</option>
                <option value="event_rejected" {{ request('action') == 'event_rejected' ? 'selected' : '' }}>Event Rejected</option>
            </select>
            <input type="date" name="date" value="{{ request('date') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="bi bi-funnel mr-2"></i>Filter
            </button>
            <a href="{{ route('admin.audit.logs') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors flex items-center">
                <i class="bi bi-arrow-clockwise mr-2"></i>Reset
            </a>
        </div>
    </form>

    <!-- Logs Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($log->action === 'organizer_approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="bi bi-person-check mr-1"></i>Approved
                                </span>
                            @elseif($log->action === 'organizer_rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="bi bi-person-x mr-1"></i>Rejected
                                </span>
                            @elseif($log->action === 'user_role_updated')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="bi bi-arrow-repeat mr-1"></i>Role Change
                                </span>
                            @elseif($log->action === 'event_approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="bi bi-check-circle mr-1"></i>Event Approved
                                </span>
                            @elseif($log->action === 'event_rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="bi bi-x-circle mr-1"></i>Event Rejected
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $log->action }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($log->user)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-700">
                                            {{ substr($log->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $log->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $log->user->email }}</p>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400">User deleted</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($log->auditable_type === 'App\Models\OrganizerVerification' && $log->metadata)
                                <div>
                                    <p class="font-medium">{{ $log->metadata['user_name'] ?? 'Unknown User' }}</p>
                                    <p class="text-xs text-gray-500">{{ $log->metadata['user_email'] ?? 'No email' }}</p>
                                </div>
                            @elseif($log->auditable_type === 'App\Models\User' && $log->auditable)
                                <div>
                                    <p class="font-medium">{{ $log->auditable->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $log->auditable->email }}</p>
                                </div>
                            @elseif($log->metadata && isset($log->metadata['user_name']))
                                <div>
                                    <p class="font-medium">{{ $log->metadata['user_name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $log->metadata['user_email'] ?? 'No email' }}</p>
                                </div>
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($log->metadata && isset($log->metadata['organization_name']))
                                {{ $log->metadata['organization_name'] }}
                            @elseif($log->metadata && isset($log->metadata['event_title']))
                                {{ $log->metadata['event_title'] }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                            {{ $log->description }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($log->metadata)
                                @if($log->action === 'organizer_approved')
                                    <div class="text-xs">
                                        <p><span class="font-medium">Status:</span> {{ $log->metadata['old_status'] ?? 'pending' }} → {{ $log->metadata['new_status'] ?? 'approved' }}</p>
                                        <p><span class="font-medium">Verified:</span> {{ $log->created_at->format('M j, Y g:i A') }}</p>
                                    </div>
                                @elseif($log->action === 'organizer_rejected')
                                    <div class="text-xs">
                                        <p><span class="font-medium">Reason:</span> {{ $log->metadata['reason'] ?? 'No reason provided' }}</p>
                                        <p><span class="font-medium">Status:</span> {{ $log->metadata['old_status'] ?? 'pending' }} → {{ $log->metadata['new_status'] ?? 'rejected' }}</p>
                                    </div>
                                @elseif($log->action === 'user_role_updated')
                                    <div class="text-xs">
                                        <p><span class="font-medium">Role:</span> {{ $log->metadata['old_role'] ?? 'unknown' }} → {{ $log->metadata['new_role'] ?? 'unknown' }}</p>
                                    </div>
                                @elseif($log->action === 'event_approved')
                                    <div class="text-xs">
                                        <p><span class="font-medium">Status:</span> {{ $log->metadata['old_status'] ?? 'pending' }} → {{ $log->metadata['new_status'] ?? 'active' }}</p>
                                    </div>
                                @elseif($log->action === 'event_rejected')
                                    <div class="text-xs">
                                        <p><span class="font-medium">Reason:</span> {{ $log->metadata['reason'] ?? 'No reason provided' }}</p>
                                        <p><span class="font-medium">Status:</span> {{ $log->metadata['old_status'] ?? 'pending' }} → {{ $log->metadata['new_status'] ?? 'rejected' }}</p>
                                    </div>
                                @endif
                            @else
                                <span class="text-gray-400">No details</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $log->ip_address ?? 'N/A' }}</code>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                            <div class="flex flex-col items-center justify-center py-8">
                                <i class="bi bi-journal-x text-4xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500">No audit logs found.</p>
                                @if(request()->hasAny(['search', 'action', 'date']))
                                    <p class="text-sm text-gray-400 mt-1">Try adjusting your filters</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} results
                </div>
                <div class="flex space-x-2">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Quick Export</h3>
            <p class="text-sm text-gray-600 mb-4">Export audit logs for reporting</p>
            <button class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center">
                <i class="bi bi-download mr-2"></i>Export CSV
            </button>
        </div>
        
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Recent Activity</h3>
            <p class="text-sm text-gray-600 mb-4">Monitor real-time admin actions</p>
            <a href="{{ route('admin.dashboard') }}" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                <i class="bi bi-speedometer2 mr-2"></i>View Dashboard
            </a>
        </div>
        
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Pending Requests</h3>
            <p class="text-sm text-gray-600 mb-4">Manage organizer verifications</p>
            <a href="{{ route('admin.organizer-verifications') }}" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center justify-center">
                <i class="bi bi-person-check mr-2"></i>View Requests
            </a>
        </div>
    </div>
</div>

<style>
    /* Custom styles for better table responsiveness */
    @media (max-width: 768px) {
        .overflow-x-auto {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
        }
    }
</style>
@endsection