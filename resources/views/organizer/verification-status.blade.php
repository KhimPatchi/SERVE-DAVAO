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
                <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-3 shadow-lg shadow-emerald-200/50">
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
            <div class="h-2 {{ $verification->status === 'approved' ? 'bg-gradient-to-r from-emerald-500 to-teal-600' : ($verification->status === 'rejected' ? 'bg-gradient-to-r from-rose-500 to-red-600' : 'bg-gradient-to-r from-slate-400 to-slate-600') }}"></div>
            
            <!-- Success Hero Section -->
            <div class="p-12 text-center {{ $verification->status === 'approved' ? 'bg-gradient-to-b from-emerald-50/50 to-white' : ($verification->status === 'rejected' ? 'bg-gradient-to-b from-rose-50/50 to-white' : 'bg-gradient-to-b from-slate-50/50 to-white') }}">
                <div class="mb-6 relative inline-block">
                    <div class="absolute inset-0 bg-emerald-200 blur-2xl opacity-20 rounded-full"></div>
                    <div class="relative flex items-center justify-center w-28 h-28 rounded-3xl bg-emerald-600 shadow-2xl rotate-3">
                        @if($verification->status === 'approved')
                            <i class="bi bi-shield-fill-check text-5xl text-white -rotate-3"></i>
                        @elseif($verification->status === 'rejected')
                            <i class="bi bi-shield-fill-x text-5xl text-rose-100 -rotate-3"></i>
                        @else
                            <i class="bi bi-shield-fill-exclamation text-5xl text-emerald-100 -rotate-3 animate-pulse"></i>
                        @endif
                    </div>
                </div>
                
                <h1 class="text-4xl font-black text-gray-900 mb-2 tracking-tight">
                    @if($verification->status === 'approved')
                        Identity Verified
                    @elseif($verification->status === 'rejected')
                        Verification Review Failed
                    @else
                        Finalizing Verification
                    @endif
                </h1>
                <div class="flex items-center justify-center gap-2 mb-4">
                    <span class="h-px w-8 bg-gray-200"></span>
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Reference: #{{ str_pad($verification->id, 6, '0', STR_PAD_LEFT) }}</p>
                    <span class="h-px w-8 bg-gray-200"></span>
                </div>
            </div>

            <!-- Body Section -->
            <div class="p-10">
                @if($verification->status === 'approved')
                    <div class="text-center mb-8">
                        <p class="text-gray-700 text-lg mb-8 leading-relaxed">🎉 Congratulations! Your identity has been successfully verified by our AI system. You now have full access to organizer features and can start creating impactful volunteer events.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <a href="{{ route('events.create') }}" class="flex items-center justify-center gap-3 px-8 py-5 bg-emerald-600 text-white rounded-2xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                                <i class="bi bi-plus-circle text-xl"></i> 
                                <span>Create Your First Event</span>
                            </a>
                            <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-3 px-8 py-5 bg-white border-2 border-emerald-100 text-emerald-700 rounded-2xl font-bold hover:bg-emerald-50 hover:border-emerald-200 transition-all">
                                <i class="bi bi-grid text-xl"></i>
                                <span>Go to Dashboard</span>
                            </a>
                        </div>
                    </div>
                @elseif($verification->status === 'rejected')
                    <div class="bg-gradient-to-br from-rose-50 to-red-50 border-2 border-rose-100 rounded-2xl p-8 mb-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-rose-100 flex items-center justify-center">
                            <i class="bi bi-exclamation-triangle-fill text-3xl text-rose-600"></i>
                        </div>
                        <h3 class="text-rose-900 font-bold text-xl mb-3">Reason for Failure</h3>
                        <p class="text-rose-800 mb-6 leading-relaxed">{{ $verification->rejection_reason ?? 'The submitted documents were unclear or did not meet our verification standards. Please ensure your ID and selfie are clear and try again.' }}</p>
                        <a href="{{ route('organizer.verification.create') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-rose-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                            <i class="bi bi-arrow-clockwise text-xl"></i> Try Again with New Photos
                        </a>
                    </div>
                @else
                    <div class="text-center">
                        <p class="text-slate-700 text-lg mb-8 leading-relaxed">Our automated AI system is currently analyzing your documents. This process typically takes between 30 seconds to 2 minutes. Please be patient!</p>
                        <div class="inline-flex items-center gap-4 px-8 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 font-bold uppercase tracking-wide">
                            <div class="flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-slate-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-slate-600"></span>
                            </div>
                            <span>🤖 AI Processing...</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Details Footer -->
            <div class="px-10 py-8 bg-white border-t border-gray-100">
                <div class="grid grid-cols-1 {{ $verification->liveness_score !== null ? 'sm:grid-cols-3' : 'sm:grid-cols-2' }} gap-8 items-center">

                    <!-- Document Type -->
                    <div>
                        <span class="block text-[11px] uppercase font-bold text-gray-400 tracking-widest mb-2">Verified Document</span>
                        <div class="flex items-center gap-2">
                            <span class="p-1.5 rounded-lg bg-slate-50 border border-slate-100 text-slate-800">
                                @if($verification->document_type == 'P')
                                    <i class="bi bi-globe2 text-teal-600"></i> Passport
                                @elseif($verification->document_type == 'D')
                                    <i class="bi bi-credit-card-2-front text-teal-600"></i> Driver's License
                                @elseif($verification->document_type == 'I')
                                    <i class="bi bi-person-vcard text-teal-600"></i> National ID
                                @else
                                    <span class="text-slate-400 font-mono">DOCUMENT</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Liveness Score (Only shown if data exists) -->
                    @if($verification->liveness_score !== null)
                        <div class="text-center sm:border-x border-gray-100">
                            <span class="block text-[11px] uppercase font-bold text-gray-400 tracking-widest mb-2">Biometric Status</span>
                            @php $ls = round($verification->liveness_score * 100); @endphp
                            @if($ls >= 60)
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-black bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">
                                    <i class="bi bi-shield-fill-check"></i> LIVE VERIFIED
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-black bg-rose-50 text-rose-700 ring-1 ring-rose-200">
                                    <i class="bi bi-exclamation-triangle-fill"></i> LIVENESS LOW
                                </span>
                            @endif
                        </div>
                    @endif

                    <!-- Last Updated -->
                    <div class="text-right">
                        <span class="block text-[11px] uppercase font-bold text-gray-400 tracking-widest mb-2">Sync Status</span>
                        <div class="flex items-center justify-end gap-2 text-slate-800 font-bold">
                            <i class="bi bi-clock-history text-slate-400"></i>
                            {{ $verification->updated_at->diffForHumans() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
