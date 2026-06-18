<x-guest-layout title="Sign in to ServeDavao">
    <div class="relative min-h-screen w-full overflow-hidden flex items-center justify-center bg-gray-50">

        <!-- Background hero image with overlay -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/img/hero1.png') }}" alt="Volunteers"
                 class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(5,150,105,0.15) 0%, rgba(4,120,87,0.25) 100%);"></div>
        </div>

        <!-- Floating Back Button -->
        <a href="{{ route('landing') }}"
           class="fixed top-6 left-6 z-50 w-10 h-10 rounded-xl bg-white/80 backdrop-blur-sm shadow-md flex items-center justify-center text-gray-600 hover:bg-white hover:scale-105 transition-all duration-300">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>

        <!-- Auth Card -->
        <div class="relative z-10 w-full max-w-md mx-auto px-6 py-8">
            <div class="bg-white rounded-3xl shadow-2xl p-10 flex flex-col items-center text-center space-y-8"
                 style="box-shadow: 0 25px 60px rgba(5,150,105,0.15), 0 10px 30px rgba(0,0,0,0.08);">

                <!-- Logo & Brand -->
                <div class="flex flex-col items-center space-y-3">
                    <div class="relative">
                        <div class="absolute -inset-2 bg-emerald-100 rounded-full blur-md opacity-70 animate-pulse"></div>
                        <img src="{{ asset('assets/img/logoDav.png') }}" alt="ServeDavao Logo"
                             class="relative w-20 h-20 rounded-full shadow-lg border-4 border-white">
                    </div>
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                            Serve<span class="text-emerald-600">Davao</span>
                        </h1>
                        <p class="text-sm text-gray-500 mt-1 font-medium">Empowering Volunteers Across Davao City</p>
                    </div>
                </div>

                <!-- Divider -->
                <div class="w-full border-t border-gray-100"></div>

                <!-- Sign-in Prompt -->
                <div class="space-y-2">
                    <h2 class="text-xl font-bold text-gray-800">Welcome!</h2>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Sign in or create an account to join our community of changemakers.
                        Use your Google account to get started instantly.
                    </p>
                </div>

                <!-- Session / Error Messages -->
                @if(session('error'))
                    <div class="w-full bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 text-left">
                        <i class="bi bi-exclamation-circle-fill text-red-500 mt-0.5 flex-shrink-0"></i>
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                @endif

                @if(session('success'))
                    <div class="w-full bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3 text-left">
                        <i class="bi bi-check-circle-fill text-emerald-500 mt-0.5 flex-shrink-0"></i>
                        <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Google Sign-In Button -->
                <div class="w-full">
                    <a href="{{ route('google.login') }}"
                       class="group w-full flex items-center justify-center gap-3 px-6 py-4 border-2 border-gray-200 rounded-2xl bg-white text-gray-700 font-semibold text-base shadow-sm hover:border-emerald-400 hover:shadow-md hover:scale-[1.02] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <!-- Google Logo -->
                        <svg class="w-6 h-6 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 533.5 544.3">
                            <path fill="#4285F4" d="M533.5 278.4c0-18.1-1.5-35.3-4.3-52H272v98.8h146.9c-6.4 34.8-25 64.4-53.6 84.2v69h86.6c50.8-46.8 80-115.8 80-199z"/>
                            <path fill="#34A853" d="M272 544.3c72.6 0 133.6-23.8 178.2-64.9l-86.6-69c-24.1 16.2-55 25.6-91.6 25.6-70.4 0-130-47.5-151.4-111.2H32v69.8C76.5 484.3 167.2 544.3 272 544.3z"/>
                            <path fill="#FBBC05" d="M120.6 324.6c-5.4-16.1-8.5-33.1-8.5-50.6s3.1-34.5 8.5-50.6v-69.8H32C11.3 206 0 241.5 0 278.4s11.3 72.5 32 100.2l88.6-69z"/>
                            <path fill="#EA4335" d="M272 107.9c39.5 0 74.9 13.6 102.8 40.4l77.1-77.1C405.7 24.7 344.7 0 272 0 167.2 0 76.5 60 32 146.6l88.6 69C142 155.4 201.6 107.9 272 107.9z"/>
                        </svg>
                        <span class="group-hover:text-emerald-700 transition-colors duration-200">Continue with Google</span>
                        <i class="bi bi-arrow-right-short text-xl text-gray-400 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all duration-200 ml-auto"></i>
                    </a>

                    <!-- Security note -->
                    <p class="mt-4 text-xs text-gray-400 text-center flex items-center justify-center gap-1.5">
                        <i class="bi bi-shield-check text-emerald-500"></i>
                        Secure authentication powered by Google OAuth 2.0
                    </p>
                </div>

                <!-- Divider -->
                <div class="w-full border-t border-gray-100"></div>

                <!-- Features / Info -->
                <div class="w-full grid grid-cols-3 gap-4 text-center">
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-9 h-9 bg-emerald-50 rounded-full flex items-center justify-center">
                            <i class="bi bi-lightning-charge-fill text-emerald-600 text-sm"></i>
                        </div>
                        <p class="text-xs text-gray-500 font-medium leading-tight">Quick<br>Sign-In</p>
                    </div>
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-9 h-9 bg-emerald-50 rounded-full flex items-center justify-center">
                            <i class="bi bi-person-check-fill text-emerald-600 text-sm"></i>
                        </div>
                        <p class="text-xs text-gray-500 font-medium leading-tight">Auto<br>Register</p>
                    </div>
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-9 h-9 bg-emerald-50 rounded-full flex items-center justify-center">
                            <i class="bi bi-lock-fill text-emerald-600 text-sm"></i>
                        </div>
                        <p class="text-xs text-gray-500 font-medium leading-tight">Secure<br>& Private</p>
                    </div>
                </div>

            </div>

            <p class="text-center text-xs text-gray-400 mt-6">
                By continuing, you agree to our
                <a href="{{ route('terms') }}" target="_blank" class="text-emerald-600 hover:underline font-medium">Terms of Service</a>
                and
                <a href="{{ route('privacy') }}" target="_blank" class="text-emerald-600 hover:underline font-medium">Privacy Policy</a>.
            </p>
        </div>

    </div>
</x-guest-layout>