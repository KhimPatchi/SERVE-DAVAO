<x-guest-layout title="Join ServeDavao">
    <div class="relative min-h-screen w-full overflow-hidden bg-white" x-data="{ 
        showRegister: {{ $showRegister ? 'true' : 'false' }},
        toggle() {
            this.showRegister = !this.showRegister;
            const url = this.showRegister ? '/register' : '/login';
            window.history.pushState({}, '', url);
        }
    }">
        
        <!-- Floating Back Button -->
        <a href="{{ route('landing') }}" 
           class="fixed top-6 left-6 z-50 w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-500 ease-in-out hover:scale-105"
           :class="!showRegister ? 'bg-white/20 text-white hover:bg-white/30 backdrop-blur-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>

        <!-- DESKTOP: Sliding Overlay Structure -->
        <div class="hidden lg:block h-full w-full absolute inset-0">
            <!-- Register Form Container (Left Side - Underneath Image initially) -->
            <div class="absolute top-0 left-0 h-full w-1/2 flex items-center justify-center px-8 py-4 lg:px-12 lg:py-8 overflow-hidden transition-all duration-700 ease-in-out z-0"
                 :class="showRegister ? 'opacity-100 translate-x-0 z-10' : 'opacity-0 -translate-x-[20%] pointer-events-none z-0'">
                
                <!-- Register Content -->
                <div class="w-full max-w-md space-y-6">
                    <div class="text-left">
                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Create an account</h2>
                        <p class="mt-2 text-gray-600">Join our community of volunteers.</p>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any() && $showRegister)
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-exclamation-circle-fill text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700 font-medium">Please fix the errors below.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                        @csrf
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-person text-gray-400"></i>
                                    </div>
                                    <input name="name" type="text" required class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition-colors" placeholder="John Doe" value="{{ old('name') }}">
                                </div>
                                @error ('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email address</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-envelope text-gray-400"></i>
                                    </div>
                                    <input name="email" type="email" required class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition-colors" placeholder="you@example.com" value="{{ old('email') }}">
                                </div>
                                @error ('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-lock text-gray-400"></i>
                                    </div>
                                    <input name="password" type="password" required class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition-colors" placeholder="Create a password">
                                </div>
                                @error ('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-shield-lock text-gray-400"></i>
                                    </div>
                                    <input name="password_confirmation" type="password" required class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition-colors" placeholder="Confirm your password">
                                </div>
                            </div>
                            
                            <!-- reCAPTCHA -->
                            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                            @error ('g-recaptcha-response') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform hover:scale-[1.02]">Create Account</button>
                    </form>

                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                        </div>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Already have an account? 
                            <button @click="toggle()" class="font-medium text-emerald-600 hover:text-emerald-500 transition-colors focus:outline-none">Sign in here</button>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Login Form Container (Right Side - Visible initially) -->
            <div class="absolute top-0 right-0 h-full w-1/2 flex items-center justify-center px-8 py-4 lg:px-12 lg:py-8 overflow-hidden transition-all duration-700 ease-in-out z-10"
                 :class="!showRegister ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-[20%] pointer-events-none'">
                
                <!-- Login Content -->
                <div class="w-full max-w-md space-y-8">
                    <div class="text-left">
                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Welcome back</h2>
                        <p class="mt-2 text-gray-600">Please enter your details to sign in.</p>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any() && !$showRegister)
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-exclamation-circle-fill text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700 font-medium">Invalid credentials. Please check your email and password.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                        @csrf
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email address</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-envelope text-gray-400"></i>
                                    </div>
                                    <input name="email" type="email" required class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition-colors" placeholder="you@example.com" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-lock text-gray-400"></i>
                                    </div>
                                    <input name="password" type="password" required class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition-colors" placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded cursor-pointer">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-600 cursor-pointer">Remember me</label>
                            </div>
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-emerald-600 hover:text-emerald-500 transition-colors">Forgot password?</a>
                            </div>
                        </div>

                        <!-- reCAPTCHA -->
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        @error ('g-recaptcha-response') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform hover:scale-[1.02]">Sign in</button>
                    </form>

                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                            <div class="relative flex justify-center text-sm"><span class="px-2 bg-white text-gray-500">Or continue with</span></div>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform hover:scale-[1.02]">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 533.5 544.3">
                                    <path fill="#4285F4" d="M533.5 278.4c0-18.1-1.5-35.3-4.3-52H272v98.8h146.9c-6.4 34.8-25 64.4-53.6 84.2v69h86.6c50.8-46.8 80-115.8 80-199z"/>
                                    <path fill="#34A853" d="M272 544.3c72.6 0 133.6-23.8 178.2-64.9l-86.6-69c-24.1 16.2-55 25.6-91.6 25.6-70.4 0-130-47.5-151.4-111.2H32v69.8C76.5 484.3 167.2 544.3 272 544.3z"/>
                                    <path fill="#FBBC05" d="M120.6 324.6c-5.4-16.1-8.5-33.1-8.5-50.6s3.1-34.5 8.5-50.6v-69.8H32C11.3 206 0 241.5 0 278.4s11.3 72.5 32 100.2l88.6-69z"/>
                                    <path fill="#EA4335" d="M272 107.9c39.5 0 74.9 13.6 102.8 40.4l77.1-77.1C405.7 24.7 344.7 0 272 0 167.2 0 76.5 60 32 146.6l88.6 69C142 155.4 201.6 107.9 272 107.9z"/>
                                </svg>
                                Sign in with Google
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Don't have an account? 
                            <button @click="toggle()" class="font-medium text-emerald-600 hover:text-emerald-500 transition-colors focus:outline-none">Sign up for free</button>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Image Panel Overlay -->
            <div class="absolute top-0 left-0 h-full w-1/2 bg-gray-900 transition-transform duration-700 ease-in-out z-20 overflow-hidden"
                 :class="{ 'translate-x-full': showRegister, 'translate-x-0': !showRegister }">
                
                <!-- Hero Image (Consistent with Landing Page) -->
                <img src="{{ asset('assets/img/hero1.png') }}" alt="Volunteers" 
                     class="absolute inset-0 w-full h-full object-cover opacity-60 transition-transform duration-[2000ms] ease-in-out"
                     :class="showRegister ? 'scale-110' : 'scale-100'">
                
                <!-- Gradient Overlay (Matches Landing Page .hero-overlay) -->
                <div class="absolute inset-0" style="background: linear-gradient(to bottom right, rgba(0, 0, 0, 0.55), rgba(26, 153, 136, 0.5));"></div>
                
                <!-- Decorative Elements -->
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-teal-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>

                <!-- Text Content Container -->
                <div class="relative z-10 h-full flex flex-col items-center justify-center px-12 text-center text-white">
                    
                    <!-- Static Logo -->
                    <div class="mb-8 flex justify-center transition-transform duration-700"
                         :class="{ 'scale-110': showRegister, 'scale-100': !showRegister }">
                        <img src="{{ asset('assets/img/logoDav.png') }}" alt="ServeDavao Logo" class="w-24 h-24 rounded-full shadow-2xl border-4 border-white/20 backdrop-blur-sm">
                    </div>

                    <!-- Login Text (Shows when panel is on LEFT) -->
                    <div class="absolute top-1/2 mt-16 transition-all duration-700 delay-100 w-full px-12 {{ !$showRegister ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8 pointer-events-none' }}"
                         :class="{ 'opacity-100 translate-y-0': !showRegister, 'opacity-0 translate-y-8 pointer-events-none': showRegister }">
                        <h1 class="text-5xl font-extrabold mb-6 tracking-tight drop-shadow-lg">ServeDavao</h1>
                        <p class="text-xl text-gray-100 font-medium leading-relaxed drop-shadow-md">
                            Join our community of changemakers. <br>
                            Connect, volunteer, and make a difference.
                        </p>
                    </div>

                    <!-- Register Text (Shows when panel is on RIGHT) -->
                    <div class="absolute top-1/2 mt-16 transition-all duration-700 delay-100 w-full px-12 {{ $showRegister ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8 pointer-events-none' }}"
                         :class="{ 'opacity-100 translate-y-0': showRegister, 'opacity-0 translate-y-8 pointer-events-none': !showRegister }">
                        <h1 class="text-5xl font-extrabold mb-6 tracking-tight drop-shadow-lg">Join Us!</h1>
                        <p class="text-xl text-gray-100 font-medium leading-relaxed drop-shadow-md">
                            Start your journey of service today. <br>
                            Create an account to find opportunities.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- MOBILE: Stacked View (Simple Toggle) -->
        <div class="lg:hidden w-full min-h-screen flex items-center justify-center p-6 bg-white">
            <!-- Mobile Login Form -->
            <div x-show="!showRegister" class="w-full max-w-md space-y-8" x-transition>
                <div class="text-center mb-8">
                    <img src="{{ asset('assets/img/logoDav.png') }}" alt="ServeDavao Logo" class="w-16 h-16 mx-auto rounded-full shadow-md mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">ServeDavao</h2>
                    <h3 class="text-xl font-bold mt-4">Welcome back</h3>
                </div>
                
                <!-- Mobile Login Form Fields (Simplified copy of desktop) -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div class="space-y-5">
                        <input name="email" type="email" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Email address" value="{{ old('email') }}">
                        <input name="password" type="password" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Password">
                    </div>
                    <button type="submit" class="w-full py-3 bg-emerald-600 text-white rounded-lg font-semibold">Sign in</button>
                </form>
                
                <div class="text-center mt-6">
                    <p class="text-gray-600">Don't have an account? <button @click="toggle()" class="text-emerald-600 font-medium">Sign up</button></p>
                </div>
            </div>

            <!-- Mobile Register Form -->
            <div x-show="showRegister" class="w-full max-w-md space-y-8" style="display: none;" x-transition>
                <div class="text-center mb-8">
                    <img src="{{ asset('assets/img/logoDav.png') }}" alt="ServeDavao Logo" class="w-16 h-16 mx-auto rounded-full shadow-md mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">ServeDavao</h2>
                    <h3 class="text-xl font-bold mt-4">Create Account</h3>
                </div>
                
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf
                    <div class="space-y-5">
                        <input name="name" type="text" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Full Name">
                        <input name="email" type="email" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Email address">
                        <input name="password" type="password" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Password">
                        <input name="password_confirmation" type="password" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Confirm Password">
                    </div>
                    <button type="submit" class="w-full py-3 bg-emerald-600 text-white rounded-lg font-semibold">Create Account</button>
                </form>
                
                <div class="text-center mt-6">
                    <p class="text-gray-600">Already have an account? <button @click="toggle()" class="text-emerald-600 font-medium">Sign in</button></p>
                </div>
            </div>
        </div>

    </div>
</x-guest-layout>