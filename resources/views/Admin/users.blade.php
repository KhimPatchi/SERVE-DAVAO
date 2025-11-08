@extends('layouts.sidebar.sidebar')

@section('title', 'User Management')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Page Header -->
        <header class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage user accounts and system access</p>
                </div>
                <div class="text-sm text-gray-500">
                    <span class="font-semibold">{{ $users->total() }}</span> total users
                </div>
            </div>
        </header>

        <!-- System Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200" role="alert">
                <div class="flex items-center">
                    <i class="bi bi-check-circle-fill text-green-500 mr-2"></i>
                    <span class="text-green-800 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200" role="alert">
                <div class="flex items-center">
                    <i class="bi bi-exclamation-triangle-fill text-red-500 mr-2"></i>
                    <span class="text-red-800 font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- User Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $users->total() }}</div>
                        <div class="text-sm text-gray-600">Total Users</div>
                    </div>
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <i class="bi bi-people text-blue-600 text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-purple-600">{{ \App\Models\User::where('role', 'admin')->count() }}</div>
                        <div class="text-sm text-gray-600">Administrators</div>
                    </div>
                    <div class="p-2 bg-purple-50 rounded-lg">
                        <i class="bi bi-shield-check text-purple-600 text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-green-600">
                            {{ \App\Models\User::whereHas('organizerVerification', fn($q) => $q->where('status', 'approved'))->count() }}
                        </div>
                        <div class="text-sm text-gray-600">Verified Organizers</div>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg">
                        <i class="bi bi-patch-check text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-yellow-600">
                            {{ \App\Models\User::whereHas('organizerVerification', fn($q) => $q->where('status', 'pending'))->count() }}
                        </div>
                        <div class="text-sm text-gray-600">Pending Review</div>
                    </div>
                    <div class="p-2 bg-yellow-50 rounded-lg">
                        <i class="bi bi-clock text-yellow-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <!-- Table Header with Search -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <h2 class="text-lg font-semibold text-gray-900">User Accounts</h2>
                        <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full" id="displayCount">
                            {{ $users->count() }} displayed
                        </span>
                    </div>
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search by name or email..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64 text-sm"
                               onkeyup="filterUsers()">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">User Information</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Account Type</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <!-- User Information -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($user->avatar)
                                                <img src="{{ asset($user->avatar) }}" 
                                                     alt="{{ $user->name }}" 
                                                     class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                                            @else
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                                    <span class="text-white font-bold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center space-x-2">
                                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                                                @if(!$user->email_verified_at)
                                                    <i class="bi bi-exclamation-triangle text-orange-500 text-xs" title="Email unverified"></i>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                            <p class="text-xs text-gray-400">ID: {{ $user->id }} • Joined {{ $user->created_at->format('M Y') }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Account Type -->
                                <td class="px-6 py-4">
                                    @if($user->isAdmin())
                                        <div class="flex items-center space-x-2 text-sm">
                                            <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                            <span class="font-medium text-gray-900">Administrator</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">Full system access</div>
                                    @elseif($user->isVerifiedOrganizer())
                                        <div class="flex items-center space-x-2 text-sm">
                                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                            <span class="font-medium text-gray-900">Verified Organizer</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">Can create events</div>
                                    @else
                                        <div class="flex items-center space-x-2 text-sm">
                                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                            <span class="font-medium text-gray-900">Volunteer</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">Basic access</div>
                                    @endif
                                </td>

                                <!-- Status - Enhanced -->
                                <td class="px-6 py-4">
                                    <div class="space-y-3">
                                        <!-- Email Verification -->
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-700">Email:</span>
                                            @if($user->email_verified_at)
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                                    <i class="bi bi-check-lg mr-1"></i>
                                                    Verified
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-50 text-orange-700 border border-orange-200">
                                                    <i class="bi bi-exclamation-triangle mr-1"></i>
                                                    Unverified
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Organizer Verification - Only show if not verified -->
                                        @if(!$user->isVerifiedOrganizer())
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-medium text-gray-700">Organizer:</span>
                                                @if($user->hasPendingVerification())
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                        <i class="bi bi-clock-history mr-1.5"></i>
                                                        Processing
                                                    </span>
                                                @elseif($user->hasRejectedVerification())
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                        <i class="bi bi-x-circle mr-1.5"></i>
                                                        Rejected
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                        <i class="bi bi-dash-circle mr-1.5"></i>
                                                        Not Applied
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Activity Status -->
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-700">Last Active:</span>
                                            <span class="text-xs text-gray-500">
                                                @if($user->last_login_at)
                                                    {{ $user->last_login_at->diffForHumans() }}
                                                @else
                                                    Never logged in
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Actions - Clean -->
                                <td class="px-6 py-4">
                                    <div class="flex justify-center">
                                        @if($user->isAdmin())
                                            <span class="inline-block px-3 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full border border-purple-200">
                                                System Admin
                                            </span>
                                        @elseif($user->hasPendingVerification())
                                            <a href="{{ route('admin.organizer-verifications') }}" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                                <i class="bi bi-clipboard-check mr-2"></i>
                                                Review Application
                                            </a>
                                        @else
                                            <!-- No action buttons for regular users -->
                                            <span class="text-xs text-gray-400">No actions available</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="text-gray-400">
                                        <i class="bi bi-people text-4xl mb-3 opacity-50"></i>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">No Users Found</h3>
                                        <p class="text-sm text-gray-500">No user accounts match your criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-sm text-gray-700">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                        </div>
                        <div>
                            {{ $users->links('vendor.pagination.simple-tailwind') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function filterUsers() {
    const searchTerm = document.querySelector('input[type="text"]').value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        if (row.cells.length < 4) return; // Skip empty row
        
        const nameElement = row.querySelector('.text-sm.font-semibold');
        const emailElement = row.querySelector('.text-gray-500');
        
        if (!nameElement || !emailElement) return;
        
        const name = nameElement.textContent.toLowerCase();
        const email = emailElement.textContent.toLowerCase();
        const isVisible = name.includes(searchTerm) || email.includes(searchTerm);
        
        row.style.display = isVisible ? '' : 'none';
        if (isVisible) visibleCount++;
    });

    // Update displayed count
    const countBadge = document.getElementById('displayCount');
    if (countBadge) {
        countBadge.textContent = `${visibleCount} displayed`;
    }
}
</script>
@endsection