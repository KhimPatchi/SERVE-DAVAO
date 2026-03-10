@extends ('layouts.sidebar.sidebar')

@section ('content')
<div class="min-h-screen bg-gray-50/60 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Professional Header -->
        <div class="mb-8">
            <nav class="mb-4 flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ route('dashboard') }}" class="transition-colors hover:text-gray-700">
                    <i class="bi bi-grid mr-1"></i>Dashboard
                </a>
                <i class="bi bi-chevron-right text-xs"></i>
                <span class="text-gray-800 font-medium">Verification Status</span>
            </nav>

            <div class="flex items-center gap-4">
                <div class="rounded-2xl bg-gradient-to-r from-purple-500 to-blue-600 p-3">
                    <i class="bi bi-shield-check text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Verification Status</h1>
                    <p class="mt-1 text-gray-600">Track your organizer verification application</p>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden ring-1 ring-gray-100">
            <!-- Status-based gradient top bar -->
            <div class="h-2 {{ $verification->status === 'approved' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : ($verification->status === 'rejected' ? 'bg-gradient-to-r from-red-500 to-rose-600' : 'bg-gradient-to-r from-blue-500 to-indigo-600') }}"></div>
            
            <!-- Header Section -->
            <div class="p-10 text-center {{ $verification->status === 'approved' ? 'bg-gradient-to-br from-green-50 to-emerald-50' : ($verification->status === 'rejected' ? 'bg-gradient-to-br from-red-50 to-rose-50' : 'bg-gradient-to-br from-blue-50 to-indigo-50') }}">
                <div class="mb-5 inline-flex items-center justify-center w-24 h-24 rounded-full {{ $verification->status === 'approved' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : ($verification->status === 'rejected' ? 'bg-gradient-to-r from-red-500 to-rose-600' : 'bg-gradient-to-r from-blue-500 to-indigo-600') }} shadow-xl">
                    @if ($verification->status === 'approved')
                        <i class="bi bi-check-circle-fill text-5xl text-white"></i>
                    @elseif ($verification->status === 'rejected')
                        <i class="bi bi-x-circle-fill text-5xl text-white"></i>
                    @else
                        <i class="bi bi-hourglass-split text-5xl text-white animate-pulse"></i>
                    @endif
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">
                    @if ($verification->status === 'approved')
                        ✓ Identity Verified!
                    @elseif ($verification->status === 'rejected')
                        Verification Failed
                    @else
                        Verification in Progress
                    @endif
                </h1>
                <p class="text-gray-600 font-medium text-lg">Application ID: <span class="font-mono">#{{ str_pad($verification->id, 6, '0', STR_PAD_LEFT) }}</span></p>
            </div>

            <!-- Body Section -->
            <div class="p-10">
                @if ($verification->status === 'approved')
                    <div class="text-center mb-8">
                        <p class="text-gray-700 text-lg mb-8 leading-relaxed">🎉 Congratulations! Your identity has been successfully verified by our AI system. You now have full access to organizer features and can start creating impactful volunteer events.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <a href="{{ route('events.create') }}" class="flex items-center justify-center gap-3 px-8 py-5 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-2xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                                <i class="bi bi-plus-circle text-xl"></i> 
                                <span>Create Your First Event</span>
                            </a>
                            <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-3 px-8 py-5 bg-white border-2 border-gray-200 text-gray-700 rounded-2xl font-bold hover:bg-gray-50 hover:border-gray-300 transition-all">
                                <i class="bi bi-grid text-xl"></i>
                                <span>Go to Dashboard</span>
                            </a>
                        </div>
                    </div>
                @elseif ($verification->status === 'rejected')
                    <div class="bg-gradient-to-br from-red-50 to-rose-50 border-2 border-red-200 rounded-2xl p-8 mb-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="bi bi-exclamation-triangle-fill text-3xl text-red-600"></i>
                        </div>
                        <h3 class="text-red-900 font-bold text-xl mb-3">Reason for Failure</h3>
                        <p class="text-red-800 mb-6 leading-relaxed">{{ $verification->rejection_reason ?? 'The submitted documents were unclear or did not meet our verification standards. Please ensure your ID and selfie are clear and try again.' }}</p>
                        <a href="{{ route('organizer.verification.create') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                            <i class="bi bi-arrow-clockwise text-xl"></i> Try Again with New Photos
                        </a>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                        <p class="font-bold mb-3 text-blue-900 flex items-center gap-2">
                            <i class="bi bi-lightbulb text-xl"></i> Tips for a successful verification:
                        </p>
                        <ul class="space-y-2 text-blue-800">
                            <li class="flex items-start gap-2">
                                <i class="bi bi-check-circle-fill text-blue-600 mt-0.5"></i>
                                <span>Ensure your government ID is current and not expired</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="bi bi-check-circle-fill text-blue-600 mt-0.5"></i>
                                <span>Use good lighting and avoid blur or glare on the ID</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="bi bi-check-circle-fill text-blue-600 mt-0.5"></i>
                                <span>Take the selfie in a well-lit area, facing the camera directly</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="bi bi-check-circle-fill text-blue-600 mt-0.5"></i>
                                <span>Remove hats, sunglasses, or any face coverings for the selfie</span>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="text-center">
                        <p class="text-gray-700 text-lg mb-8 leading-relaxed">Our automated AI system is currently analyzing your documents. This process typically takes between 30 seconds to 2 minutes. Please be patient!</p>
                        <div class="inline-flex items-center gap-4 px-8 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl text-blue-700 font-bold uppercase tracking-wide">
                            <div class="flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-600"></span>
                            </div>
                            <span>🤖 AI Processing...</span>
                        </div>
                        <button onclick="window.location.reload()" class="mt-10 inline-flex items-center gap-2 px-6 py-3 text-gray-600 hover:text-purple-600 transition-all font-semibold border-2 border-gray-200 hover:border-purple-300 rounded-xl">
                            <i class="bi bi-arrow-clockwise text-xl"></i> Refresh Status
                        </button>
                    </div>
                @endif
            </div>

            <!-- Details Footer -->
            <div class="px-10 py-6 bg-gradient-to-r from-gray-50 to-slate-50 border-t border-gray-200 flex justify-between items-center">
                <div>
                    <span class="block text-xs uppercase font-bold text-gray-400 tracking-wider mb-1">Document Type</span>
                    <span class="text-base font-bold text-gray-800">
                        @if ($verification->document_type == 'P')
                            <i class="bi bi-globe2 text-purple-600"></i> Passport
                        @elseif ($verification->document_type == 'D')
                            <i class="bi bi-credit-card-2-front text-blue-600"></i> Driver's License
                        @elseif ($verification->document_type == 'I')
                            <i class="bi bi-person-vcard text-indigo-600"></i> National ID
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div class="text-right">
                    <span class="block text-xs uppercase font-bold text-gray-400 tracking-wider mb-1">Last Updated</span>
                    <span class="text-base font-bold text-gray-800">{{ $verification->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
