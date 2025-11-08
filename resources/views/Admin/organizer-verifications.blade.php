@extends('layouts.sidebar.sidebar')

@section('title', 'Organizer Verification Management')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Page Header -->
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Organizer Verification Management</h1>
            <p class="mt-2 text-sm text-gray-600">Review and manage organizer verification applications submitted by users.</p>
        </header>

        <!-- System Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-md bg-green-50 border border-green-200" role="alert" aria-live="polite">
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

        @if(session('error'))
            <div class="mb-6 p-4 rounded-md bg-red-50 border border-red-200" role="alert" aria-live="assertive">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-exclamation-triangle-fill text-red-400 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Error</h3>
                        <div class="mt-1 text-sm text-red-700">
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Applications Table Section -->
        <section aria-labelledby="applications-heading">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-lg overflow-hidden">
                <!-- Table Header -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 id="applications-heading" class="text-lg font-semibold text-gray-900">Pending Verification Applications</h2>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $applications->total() }} application(s) awaiting review
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="bi bi-clock-history mr-1"></i>
                                Requires Attention
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Responsive Table Container -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" aria-describedby="applications-heading">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Applicant Information</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Organization Details</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Verification Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Submission Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Administrative Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($applications as $verification)
                                @php
                                    $user = $verification->user;
                                    $organizedEvents = $user->organizedEvents ?? collect();
                                    $pendingEventsCount = $organizedEvents->where('status', 'pending')->count();
                                    $latestEvent = $organizedEvents->where('status', 'pending')->first();
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors duration-150" aria-rowindex="{{ $loop->index + 1 }}">
                                    <!-- Applicant Information -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if($user->avatar)
                                                    <img src="{{ asset($user->avatar) }}" 
                                                         alt="{{ $user->name }}'s profile picture" 
                                                         class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
                                                        <span class="text-white text-sm font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                <div class="text-xs text-gray-400 mt-1">User ID: {{ $user->id }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Organization Details -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $verification->organization_name }}</div>
                                        <div class="text-xs text-gray-600 capitalize mt-1">
                                            {{ str_replace('_', ' ', $verification->organization_type) }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            ID: {{ $verification->identification_number }}
                                        </div>
                                    </td>

                                    <!-- Verification Status -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col space-y-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 w-fit">
                                                <i class="bi bi-person-gear mr-1"></i>
                                                Verification Pending
                                            </span>
                                            @if($pendingEventsCount > 0)
                                                <div class="text-xs text-gray-600">
                                                    <span class="font-medium">{{ $pendingEventsCount }}</span> event(s) awaiting approval
                                                </div>
                                                @if($latestEvent)
                                                    <div class="text-xs text-gray-500 truncate max-w-xs" title="{{ $latestEvent->title }}">
                                                        Latest: {{ Str::limit($latestEvent->title, 35) }}
                                                    </div>
                                                @endif
                                            @else
                                                <div class="text-xs text-gray-500">No pending events</div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Submission Date -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex flex-col">
                                            <time datetime="{{ $verification->created_at->toIso8601String() }}">
                                                {{ $verification->created_at->format('M j, Y') }}
                                            </time>
                                            <div class="text-xs text-gray-500">
                                                {{ $verification->created_at->format('g:i A') }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Administrative Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex flex-col space-y-2">
                                            <button type="button" 
                                                    onclick="openApproveModal({{ $user->id }})" 
                                                    class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 shadow-sm"
                                                    aria-label="Approve organizer application for {{ $user->name }}">
                                                <i class="bi bi-check-lg mr-1.5"></i>
                                                Approve
                                            </button>
                                            <button type="button" 
                                                    onclick="openRejectModal({{ $user->id }})" 
                                                    class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 shadow-sm"
                                                    aria-label="Reject organizer application for {{ $user->name }}">
                                                <i class="bi bi-x-lg mr-1.5"></i>
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="bi bi-check-all text-5xl mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">All Applications Processed</h3>
                                            <p class="text-sm text-gray-500 max-w-md">
                                                There are currently no pending organizer verification applications requiring review.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($applications->hasPages())
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <nav class="flex items-center justify-between" aria-label="Pagination">
                            <div class="text-sm text-gray-700">
                                Showing 
                                <span class="font-medium">{{ $applications->firstItem() }}</span>
                                to 
                                <span class="font-medium">{{ $applications->lastItem() }}</span>
                                of
                                <span class="font-medium">{{ $applications->total() }}</span>
                                results
                            </div>
                            <div class="flex justify-end">
                                {{ $applications->links('vendor.pagination.tailwind') }}
                            </div>
                        </nav>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<!-- Action Modals -->
@foreach($applications as $verification)
    @php $user = $verification->user; @endphp
    
    <!-- Approval Confirmation Modal -->
    <div id="approveModal{{ $user->id }}" 
         class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full hidden z-50" 
         role="dialog" 
         aria-labelledby="approve-modal-title-{{ $user->id }}"
         aria-modal="true">
        <div class="relative top-20 mx-auto p-5 w-full max-w-md">
            <div class="bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5">
                <div class="p-6">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-center w-12 h-12 mx-auto rounded-full bg-green-100 mb-4">
                        <i class="bi bi-check-lg text-green-600 text-xl" aria-hidden="true"></i>
                    </div>
                    <h3 id="approve-modal-title-{{ $user->id }}" class="text-lg font-semibold text-gray-900 text-center mb-2">
                        Approve Organizer Application
                    </h3>
                    
                    <!-- Modal Content -->
                    <div class="mt-4 space-y-3">
                        <p class="text-sm text-gray-600 text-center">
                            You are about to approve <strong class="text-gray-900">{{ $user->name }}</strong> as a verified organizer.
                        </p>
                        <div class="bg-gray-50 p-3 rounded-md">
                            <dl class="grid grid-cols-1 gap-2 text-xs">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Organization:</dt>
                                    <dd class="font-medium text-gray-900">{{ $verification->organization_name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Organization Type:</dt>
                                    <dd class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $verification->organization_type) }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-md border border-blue-200">
                            <p class="text-xs text-blue-700 flex items-start">
                                <i class="bi bi-info-circle mr-2 mt-0.5 flex-shrink-0"></i>
                                This action will grant organizer privileges and automatically approve all pending events.
                            </p>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeApproveModal({{ $user->id }})"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                aria-label="Cancel approval process">
                            Cancel
                        </button>
                        <form action="{{ route('admin.organizer-verifications.approve', $user) }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                                    aria-label="Confirm approval of organizer application">
                                Confirm Approval
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Confirmation Modal -->
    <div id="rejectModal{{ $user->id }}" 
         class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full hidden z-50" 
         role="dialog" 
         aria-labelledby="reject-modal-title-{{ $user->id }}"
         aria-modal="true">
        <div class="relative top-20 mx-auto p-5 w-full max-w-md">
            <div class="bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5">
                <form action="{{ route('admin.organizer-verifications.reject', $user) }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="p-6">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-center w-12 h-12 mx-auto rounded-full bg-red-100 mb-4">
                            <i class="bi bi-x-lg text-red-600 text-xl" aria-hidden="true"></i>
                        </div>
                        <h3 id="reject-modal-title-{{ $user->id }}" class="text-lg font-semibold text-gray-900 text-center mb-2">
                            Reject Organizer Application
                        </h3>
                        
                        <!-- Modal Content -->
                        <div class="mt-4 space-y-3">
                            <p class="text-sm text-gray-600 text-center">
                                You are about to reject the organizer application for <strong class="text-gray-900">{{ $user->name }}</strong>.
                            </p>
                            <div class="bg-gray-50 p-3 rounded-md">
                                <dl class="grid grid-cols-1 gap-2 text-xs">
                                    <div class="flex justify-between">
                                        <dt class="text-gray-500">Organization:</dt>
                                        <dd class="font-medium text-gray-900">{{ $verification->organization_name }}</dd>
                                    </div>
                                </dl>
                            </div>
                            <div class="mt-4">
                                <label for="reason{{ $user->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    Rejection Reason
                                    <span class="text-gray-400 font-normal">(Recommended)</span>
                                </label>
                                <textarea id="reason{{ $user->id }}" 
                                          name="reason" 
                                          rows="4"
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm resize-vertical"
                                          placeholder="Provide specific feedback to help the applicant understand the decision..."
                                          aria-describedby="reason-help-{{ $user->id }}"></textarea>
                                <p id="reason-help-{{ $user->id }}" class="mt-1 text-xs text-gray-500">
                                    This feedback will be visible to the applicant.
                                </p>
                            </div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" 
                                    onclick="closeRejectModal({{ $user->id }})"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                    aria-label="Cancel rejection process">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                    aria-label="Confirm rejection of organizer application">
                                Confirm Rejection
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<script>
/**
 * Organizer Verification Management Script
 * Handles modal interactions for application review process
 */

// Modal Management Functions
function openApproveModal(userId) {
    const modal = document.getElementById(`approveModal${userId}`);
    if (modal) {
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        // Trap focus within modal
        trapFocus(modal);
    }
}

function closeApproveModal(userId) {
    const modal = document.getElementById(`approveModal${userId}`);
    if (modal) {
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
    }
}

function openRejectModal(userId) {
    const modal = document.getElementById(`rejectModal${userId}`);
    if (modal) {
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        // Focus on textarea when opening reject modal
        const textarea = modal.querySelector('textarea');
        if (textarea) {
            setTimeout(() => textarea.focus(), 100);
        }
        trapFocus(modal);
    }
}

function closeRejectModal(userId) {
    const modal = document.getElementById(`rejectModal${userId}`);
    if (modal) {
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
    }
}

// Accessibility: Trap focus within modal
function trapFocus(modal) {
    const focusableElements = modal.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];

    function handleTabKey(e) {
        if (e.key === 'Tab') {
            if (e.shiftKey) {
                if (document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                }
            } else {
                if (document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        }
    }

    modal.addEventListener('keydown', handleTabKey);
    
    // Store the handler for cleanup
    modal._tabHandler = handleTabKey;
}

// Cleanup function for modal event listeners
function cleanupModalEvents() {
    @foreach($applications as $verification)
        @php $user = $verification->user; @endphp
        const approveModal{{ $user->id }} = document.getElementById('approveModal{{ $user->id }}');
        const rejectModal{{ $user->id }} = document.getElementById('rejectModal{{ $user->id }}');
        
        if (approveModal{{ $user->id }} && approveModal{{ $user->id }}._tabHandler) {
            approveModal{{ $user->id }}.removeEventListener('keydown', approveModal{{ $user->id }}._tabHandler);
        }
        if (rejectModal{{ $user->id }} && rejectModal{{ $user->id }}._tabHandler) {
            rejectModal{{ $user->id }}.removeEventListener('keydown', rejectModal{{ $user->id }}._tabHandler);
        }
    @endforeach
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Close modals when clicking on backdrop
    window.addEventListener('click', function(event) {
        @foreach($applications as $verification)
            @php $user = $verification->user; @endphp
            if (event.target.id === 'approveModal{{ $user->id }}') {
                closeApproveModal({{ $user->id }});
            }
            if (event.target.id === 'rejectModal{{ $user->id }}') {
                closeRejectModal({{ $user->id }});
            }
        @endforeach
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            @foreach($applications as $verification)
                @php $user = $verification->user; @endphp
                closeApproveModal({{ $user->id }});
                closeRejectModal({{ $user->id }});
            @endforeach
        }
    });
});

// Cleanup on page unload
window.addEventListener('beforeunload', cleanupModalEvents);
</script>
@endsection