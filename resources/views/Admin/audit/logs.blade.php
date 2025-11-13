@extends('layouts.sidebar.sidebar')

@section('title', 'Audit Logs - ServeDavao')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Audit Logs</h1>
        <p class="text-gray-600 mt-2">Track all system activities and administrator actions</p>
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
    <form method="GET" action="{{ route('admin.admin.audit.logs') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 mb-6">
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
            <a href="{{ route('admin.admin.audit.logs') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors flex items-center">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                    @php
                        // Get admin user with avatar
                        $adminUser = $log->user;
                        $adminAvatarUrl = null;
                        if ($adminUser) {
                            if ($adminUser->google_avatar) {
                                $adminAvatarUrl = $adminUser->google_avatar;
                            } elseif ($adminUser->avatar) {
                                if (str_starts_with($adminUser->avatar, 'http')) {
                                    $adminAvatarUrl = $adminUser->avatar;
                                } elseif (str_starts_with($adminUser->avatar, 'storage/')) {
                                    $adminAvatarUrl = asset($adminUser->avatar);
                                } else {
                                    $adminAvatarUrl = asset('storage/' . $adminUser->avatar);
                                }
                            }
                            $adminHasValidAvatar = $adminAvatarUrl && filter_var($adminAvatarUrl, FILTER_VALIDATE_URL);
                            $adminInitial = strtoupper(substr($adminUser->name, 0, 1));
                        }

                        // Get target user with avatar
                        $targetUser = null;
                        $targetAvatarUrl = null;
                        if($log->auditable_type === 'App\Models\OrganizerVerification' && $log->metadata) {
                            $targetUser = \App\Models\User::where('email', $log->metadata['user_email'] ?? '')->first();
                        } elseif($log->auditable_type === 'App\Models\User' && $log->auditable) {
                            $targetUser = $log->auditable;
                        } elseif($log->metadata && isset($log->metadata['user_email'])) {
                            $targetUser = \App\Models\User::where('email', $log->metadata['user_email'])->first();
                        }

                        if ($targetUser) {
                            if ($targetUser->google_avatar) {
                                $targetAvatarUrl = $targetUser->google_avatar;
                            } elseif ($targetUser->avatar) {
                                if (str_starts_with($targetUser->avatar, 'http')) {
                                    $targetAvatarUrl = $targetUser->avatar;
                                } elseif (str_starts_with($targetUser->avatar, 'storage/')) {
                                    $targetAvatarUrl = asset($targetUser->avatar);
                                } else {
                                    $targetAvatarUrl = asset('storage/' . $targetUser->avatar);
                                }
                            }
                            $targetHasValidAvatar = $targetAvatarUrl && filter_var($targetAvatarUrl, FILTER_VALIDATE_URL);
                            $targetInitial = strtoupper(substr($targetUser->name, 0, 1));
                        }
                    @endphp
                    
                    <tr class="hover:bg-gray-50">
                        <!-- Timestamp -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="font-medium">{{ $log->created_at->format('M j, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $log->created_at->format('g:i A') }}</div>
                        </td>

                        <!-- Action -->
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

                        <!-- Administrator with Avatar -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($adminUser)
                                <div class="flex items-center">
                                    @if(isset($adminHasValidAvatar) && $adminHasValidAvatar)
                                        <img src="{{ $adminAvatarUrl }}" alt="{{ $adminUser->name }}"
                                             class="w-8 h-8 rounded-full border-2 border-gray-200 object-cover mr-3">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-semibold mr-3">
                                            {{ $adminInitial }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $adminUser->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $adminUser->email }}</p>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400">User deleted</span>
                            @endif
                        </td>

                        <!-- Target User with Avatar -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($targetUser)
                                <div class="flex items-center">
                                    @if(isset($targetHasValidAvatar) && $targetHasValidAvatar)
                                        <img src="{{ $targetAvatarUrl }}" alt="{{ $targetUser->name }}"
                                             class="w-8 h-8 rounded-full border-2 border-gray-200 object-cover mr-3">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-green-500 to-teal-600 flex items-center justify-center text-white text-sm font-semibold mr-3">
                                            {{ $targetInitial }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $targetUser->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $targetUser->email }}</p>
                                    </div>
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

                        <!-- Contact Information -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($log->metadata && isset($log->metadata['phone']))
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-telephone text-gray-400"></i>
                                    <span class="font-mono text-xs">{{ $log->metadata['phone'] }}</span>
                                </div>
                            @elseif($log->metadata && isset($log->metadata['user_phone']))
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-telephone text-gray-400"></i>
                                    <span class="font-mono text-xs">{{ $log->metadata['user_phone'] }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>

                        <!-- Organization -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($log->metadata && isset($log->metadata['organization_name']))
                                <div class="font-medium">{{ $log->metadata['organization_name'] }}</div>
                                @if(isset($log->metadata['organization_type']))
                                    <div class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $log->metadata['organization_type']) }}</div>
                                @endif
                            @elseif($log->metadata && isset($log->metadata['event_title']))
                                <div class="font-medium">{{ $log->metadata['event_title'] }}</div>
                                <div class="text-xs text-gray-500">Event</div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <!-- IP Address -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $log->ip_address ?? 'N/A' }}</code>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
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