@extends('layouts.sidebar.sidebar')

@section('title', 'Verification Status')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Organizer Verification Status</h1>
            <p class="mt-2 text-gray-600">Track your organizer verification application</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Status Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="text-center">
                @if($verification->status === 'pending')
                    <!-- Pending Status -->
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-hourglass-split text-yellow-600 text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-yellow-700 mb-2">Pending Review</h2>
                        <p class="text-gray-600 mb-4">
                            Your organizer verification application is currently under review by our admin team.
                            This process usually takes 1-3 business days.
                        </p>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-sm text-yellow-700">
                                <strong>Submitted on:</strong> {{ $verification->created_at->format('F j, Y \a\t g:i A') }}
                            </p>
                        </div>
                    </div>

                @elseif($verification->status === 'approved')
                    <!-- Approved Status -->
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-check-circle text-green-600 text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-green-700 mb-2">Approved!</h2>
                        <p class="text-gray-600 mb-4">
                            Congratulations! Your organizer verification has been approved.
                            You can now create and manage events.
                        </p>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-green-700">
                                <strong>Approved on:</strong> {{ $verification->approved_at->format('F j, Y \a\t g:i A') }}
                            </p>
                        </div>
                    </div>

                @elseif($verification->status === 'rejected')
                    <!-- Rejected Status -->
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-x-circle text-red-600 text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-red-700 mb-2">Application Rejected</h2>
                        <p class="text-gray-600 mb-4">
                            Unfortunately, your organizer verification application was not approved.
                        </p>
                        @if($verification->rejection_reason)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                <p class="text-sm font-semibold text-red-700 mb-2">Reason for rejection:</p>
                                <p class="text-red-600">{{ $verification->rejection_reason }}</p>
                            </div>
                        @endif
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-sm text-red-700">
                                <strong>Rejected on:</strong> {{ $verification->rejected_at->format('F j, Y \a\t g:i A') }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Application Details -->
            <div class="border-t border-gray-200 pt-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Application Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Organization Name</label>
                        <p class="mt-1 text-gray-900">{{ $verification->organization_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Organization Type</label>
                        <p class="mt-1 text-gray-900 capitalize">{{ str_replace('_', ' ', $verification->organization_type) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <p class="mt-1 text-gray-900">{{ $verification->phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ID Number</label>
                        <p class="mt-1 text-gray-900">{{ $verification->identification_number }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <p class="mt-1 text-gray-900">{{ $verification->address }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">ID Document</label>
                        <a href="{{ Storage::url($verification->identification_document_path) }}" 
                           target="_blank" 
                           class="inline-flex items-center text-blue-600 hover:text-blue-800 mt-1">
                            <i class="bi bi-eye mr-2"></i>
                            View Uploaded Document
                        </a>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="border-t border-gray-200 pt-6 mt-6">
                @if($verification->status === 'rejected')
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('organizer.verification.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200 text-center">
                            Submit New Application
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200 text-center">
                            Back to Dashboard
                        </a>
                    </div>
                @else
                    <div class="flex justify-center">
                        <a href="{{ route('dashboard') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                            Back to Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Help Section -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-2">Need Help?</h3>
            <p class="text-blue-700 mb-4">
                If you have any questions about your verification status or need to update your application information, 
                please contact our support team.
            </p>
            <div class="flex items-center text-blue-600">
                <i class="bi bi-envelope mr-2"></i>
                <span>support@servedavao.com</span>
            </div>
        </div>
    </div>
</div>
@endsection