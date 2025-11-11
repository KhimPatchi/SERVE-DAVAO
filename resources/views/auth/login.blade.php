<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logoDav.png') }}">
    <title>ServeDavao Login</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            500: '#1a9988',
                            600: '#147a6c',
                            700: '#0f5c52',
                        }
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                        'slide-left': 'slideLeft 0.6s ease-out',
                        'slide-right': 'slideRight 0.6s ease-out',
                    }
                }
            }
        }
    </script>
    
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes slideLeft {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideRight {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .page-transition {
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .slide-out-left {
            transform: translateX(-100%);
            opacity: 0;
        }
        
        .slide-out-right {
            transform: translateX(100%);
            opacity: 0;
        }
        
        .slide-in-left {
            animation: slideLeft 0.6s ease-out;
        }
        
        .slide-in-right {
            animation: slideRight 0.6s ease-out;
        }
        
        /* Auto-adjusting container */
        .auto-height-container {
            min-height: 600px;
            max-height: 90vh;
            height: auto;
        }
        
        /* Scrollable content for register form */
        .scrollable-content {
            max-height: calc(90vh - 100px);
            overflow-y: auto;
        }
        
        /* Hide scrollbar for better appearance */
        .scrollable-content::-webkit-scrollbar {
            width: 4px;
        }
        
        .scrollable-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .scrollable-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        .scrollable-content::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Ensure forms are properly sized */
        .form-container {
            width: 100%;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#0f2027] via-[#203a43] to-[#2c5364] min-h-screen flex items-center justify-center p-4 font-inter overflow-hidden">

    <!-- Prevent logged-in users from accessing login -->
    @if (Auth::check())
        <script>window.location.href = "{{ route('dashboard') }}";</script>
    @endif

    <!-- Main Container -->
    <div class="w-full max-w-6xl flex flex-col lg:flex-row rounded-2xl shadow-2xl overflow-hidden bg-white auto-height-container">
        
        <!-- Left Side - Brand (Static) -->
        <div class="lg:w-1/2 bg-gradient-to-br from-primary-600 to-primary-800 p-8 text-white relative">
            <div class="relative z-10 h-full flex flex-col justify-center">
                <!-- Login Content -->
                <div id="loginContent">
                    <div class="mb-8">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm animate-float">
                            <i class="bi bi-people-fill text-2xl text-white"></i>
                        </div>
                        <h1 class="text-4xl font-bold mb-4">ServeDavao</h1>
                        <p class="text-primary-100 text-lg">Empowering volunteers to serve the Davao community.</p>
                    </div>
                    
                    <div class="space-y-4 mt-8">
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-primary-400/30 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-check-lg text-primary-200 text-sm"></i>
                            </div>
                            <span class="text-primary-100">Join thousands of volunteers</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-primary-400/30 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-check-lg text-primary-200 text-sm"></i>
                            </div>
                            <span class="text-primary-100">Make a real impact</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-primary-400/30 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-check-lg text-primary-200 text-sm"></i>
                            </div>
                            <span class="text-primary-100">Track your contributions</span>
                        </div>
                    </div>
                </div>

                <!-- Register Content (Hidden) -->
                <div id="registerContent" class="hidden">
                    <div class="mb-8">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm animate-float">
                            <i class="bi bi-person-plus-fill text-2xl text-white"></i>
                        </div>
                        <h1 class="text-4xl font-bold mb-4">Join Our Community</h1>
                        <p class="text-primary-100 text-lg">Start your volunteer journey and make a difference today.</p>
                    </div>
                    
                    <div class="space-y-4 mt-8">
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-primary-400/30 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-check-lg text-primary-200 text-sm"></i>
                            </div>
                            <span class="text-primary-100">Easy registration process</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-primary-400/30 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-check-lg text-primary-200 text-sm"></i>
                            </div>
                            <span class="text-primary-100">Connect with fellow volunteers</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-primary-400/30 rounded-full flex items-center justify-center mr-3">
                                <i class="bi bi-check-lg text-primary-200 text-sm"></i>
                            </div>
                            <span class="text-primary-100">Flexible volunteering schedule</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Forms Container -->
        <div class="lg:w-1/2 p-8 relative overflow-hidden">
            <!-- Back Button -->
            <a href="{{ route('landing') }}" class="absolute top-6 left-6 w-10 h-10 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl flex items-center justify-center transition-all duration-300 hover:scale-105 z-20">
                <i class="bi bi-arrow-left"></i>
            </a>
            
            <!-- Login Form -->
            <div id="loginPage" class="form-container page-transition h-full">
                <div class="scrollable-content w-full max-w-md mx-auto py-4">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                        <p class="text-gray-600">Sign in to your account</p>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                            <strong>Invalid credentials:</strong> Please check your email and password.
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                            <strong>Error:</strong> {{ session('error') }}
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        <div class="space-y-5">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" id="email" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300"
                                       placeholder="you@example.com" value="{{ old('email') }}">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 pr-12"
                                           placeholder="••••••••">
                                    <span class="absolute right-3 top-3 text-gray-500 cursor-pointer hover:text-primary-600 transition-colors duration-200" id="togglePassword">
                                        <i class="bi bi-eye-slash"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500" 
                                           type="checkbox" name="remember" id="remember">
                                    <label class="ml-2 block text-sm text-gray-700" for="remember">Remember me</label>
                                </div>
                                <a href="#" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Forgot password?</a>
                            </div>

                            <button type="submit" id="loginButton" 
                                    class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                                <i class="bi bi-box-arrow-in-right mr-2"></i> 
                                <span id="loginText">Sign In</span>
                                <div id="loginSpinner" class="hidden animate-spin rounded-full h-5 w-5 border-b-2 border-white ml-2"></div>
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="flex items-center my-6">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="mx-4 text-gray-500 text-sm">OR</span>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>

                    <!-- Google Login -->
                    <a href="{{ route('google.login') }}" 
                       class="w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 533.5 544.3" class="mr-3">
                            <path fill="#4285F4" d="M533.5 278.4c0-18.1-1.5-35.3-4.3-52H272v98.8h146.9c-6.4 34.8-25 64.4-53.6 84.2v69h86.6c50.8-46.8 80-115.8 80-199z"/>
                            <path fill="#34A853" d="M272 544.3c72.6 0 133.6-23.8 178.2-64.9l-86.6-69c-24.1 16.2-55 25.6-91.6 25.6-70.4 0-130-47.5-151.4-111.2H32v69.8C76.5 484.3 167.2 544.3 272 544.3z"/>
                            <path fill="#FBBC05" d="M120.6 324.6c-5.4-16.1-8.5-33.1-8.5-50.6s3.1-34.5 8.5-50.6v-69.8H32C11.3 206 0 241.5 0 278.4s11.3 72.5 32 100.2l88.6-69z"/>
                            <path fill="#EA4335" d="M272 107.9c39.5 0 74.9 13.6 102.8 40.4l77.1-77.1C405.7 24.7 344.7 0 272 0 167.2 0 76.5 60 32 146.6l88.6 69C142 155.4 201.6 107.9 272 107.9z"/>
                        </svg>
                        Sign in with Google
                    </a>

                    <p class="text-center text-gray-600 text-sm mt-6">
                        Don't have an account? 
                        <button id="goToRegister" class="text-primary-600 hover:text-primary-700 font-semibold transition-colors duration-200">Register here</button>
                    </p>
                </div>
            </div>

            <!-- Register Form (Hidden by default) -->
            <div id="registerPage" class="form-container page-transition absolute inset-0 p-8 transform translate-x-full opacity-0 h-full">
                <div class="scrollable-content w-full max-w-md mx-auto py-4">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h2>
                        <p class="text-gray-600">Join our community of volunteers</p>
                    </div>

                    <!-- Success/Error Messages -->
                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Register Form -->
                    <form method="POST" action="{{ route('register') }}" novalidate id="registerForm">
                        @csrf
                        <div class="space-y-5">
                            <!-- Full Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input 
                                    id="name" 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300"
                                    placeholder="Enter your full name">
                                @error('name')
                                    <span class="text-red-500 text-sm mt-2 block">⚠ {{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="registerEmail" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input 
                                    id="registerEmail" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300"
                                    placeholder="Enter your email">
                                @error('email')
                                    <span class="text-red-500 text-sm mt-2 block">⚠ {{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="registerPassword" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <div class="relative">
                                    <input 
                                        id="registerPassword" 
                                        type="password" 
                                        name="password" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 pr-12"
                                        placeholder="Create a password">
                                    <span class="absolute right-3 top-3 text-gray-500 cursor-pointer hover:text-primary-600 transition-colors duration-200" id="toggleRegisterPassword">
                                        <i class="bi bi-eye-slash"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <span class="text-red-500 text-sm mt-2 block">⚠ {{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                <div class="relative">
                                    <input 
                                        id="password_confirmation" 
                                        type="password" 
                                        name="password_confirmation" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 pr-12"
                                        placeholder="Confirm your password">
                                    <span class="absolute right-3 top-3 text-gray-500 cursor-pointer hover:text-primary-600 transition-colors duration-200" id="toggleConfirmPassword">
                                        <i class="bi bi-eye-slash"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Terms Agreement -->
                            <div class="flex items-start mt-4">
                                <input class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 mt-1" 
                                       type="checkbox" name="terms" id="terms" required>
                                <label class="ml-3 block text-sm text-gray-700" for="terms">
                                    I agree to the <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">Terms of Service</a> and <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">Privacy Policy</a>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="registerButton" 
                                    class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center mt-4">
                                <i class="bi bi-person-plus-fill mr-2"></i> 
                                <span id="registerText">Create Account</span>
                                <div id="registerSpinner" class="hidden animate-spin rounded-full h-5 w-5 border-b-2 border-white ml-2"></div>
                            </button>
                        </div>
                    </form>

                    <p class="text-center text-gray-600 text-sm mt-6">
                        Already have an account? 
                        <button id="goToLogin" class="text-primary-600 hover:text-primary-700 font-semibold transition-colors duration-200">Sign in here</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const loginPage = document.getElementById('loginPage');
        const registerPage = document.getElementById('registerPage');
        const loginContent = document.getElementById('loginContent');
        const registerContent = document.getElementById('registerContent');
        const goToRegister = document.getElementById('goToRegister');
        const goToLogin = document.getElementById('goToLogin');

        // Slide to Register
        goToRegister.addEventListener('click', () => {
            // Slide out login to the left
            loginPage.classList.add('slide-out-left');
            
            // Update left side content
            loginContent.classList.add('hidden');
            registerContent.classList.remove('hidden');
            
            // After transition, hide login and show register
            setTimeout(() => {
                loginPage.style.display = 'none';
                registerPage.classList.remove('translate-x-full', 'opacity-0');
                registerPage.style.display = 'flex';
                registerPage.classList.add('slide-in-right');
                
                // Auto-adjust container height for register form
                document.querySelector('.auto-height-container').classList.add('max-h-[95vh]');
            }, 300);
        });

        // Slide to Login
        goToLogin.addEventListener('click', () => {
            // Slide out register to the right
            registerPage.classList.add('slide-out-right');
            
            // Update left side content
            registerContent.classList.add('hidden');
            loginContent.classList.remove('hidden');
            
            // After transition, hide register and show login
            setTimeout(() => {
                registerPage.classList.add('translate-x-full', 'opacity-0');
                registerPage.classList.remove('slide-in-right');
                registerPage.style.display = 'none';
                loginPage.style.display = 'flex';
                loginPage.classList.remove('slide-out-left');
                loginPage.classList.add('slide-in-left');
                
                // Reset container height for login form
                document.querySelector('.auto-height-container').classList.remove('max-h-[95vh]');
                
                // Reset animation after it completes
                setTimeout(() => {
                    loginPage.classList.remove('slide-in-left');
                }, 600);
            }, 300);
        });

        // Password toggle functionality
        const togglePassword = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("password");
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener("click", () => {
                const type = passwordInput.type === "password" ? "text" : "password";
                passwordInput.type = type;
                const icon = togglePassword.querySelector('i');
                icon.classList.toggle("bi-eye");
                icon.classList.toggle("bi-eye-slash");
            });
        }

        const toggleRegisterPassword = document.getElementById("toggleRegisterPassword");
        const registerPasswordInput = document.getElementById("registerPassword");
        
        if (toggleRegisterPassword && registerPasswordInput) {
            toggleRegisterPassword.addEventListener("click", () => {
                const type = registerPasswordInput.type === "password" ? "text" : "password";
                registerPasswordInput.type = type;
                const icon = toggleRegisterPassword.querySelector('i');
                icon.classList.toggle("bi-eye");
                icon.classList.toggle("bi-eye-slash");
            });
        }

        const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
        const confirmPasswordInput = document.getElementById("password_confirmation");
        
        if (toggleConfirmPassword && confirmPasswordInput) {
            toggleConfirmPassword.addEventListener("click", () => {
                const type = confirmPasswordInput.type === "password" ? "text" : "password";
                confirmPasswordInput.type = type;
                const icon = toggleConfirmPassword.querySelector('i');
                icon.classList.toggle("bi-eye");
                icon.classList.toggle("bi-eye-slash");
            });
        }

        // Form submission animations
        const loginForm = document.getElementById('loginForm');
        const loginButton = document.getElementById('loginButton');
        const loginText = document.getElementById('loginText');
        const loginSpinner = document.getElementById('loginSpinner');

        const registerForm = document.getElementById('registerForm');
        const registerButton = document.getElementById('registerButton');
        const registerText = document.getElementById('registerText');
        const registerSpinner = document.getElementById('registerSpinner');

        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                loginText.textContent = 'Signing in...';
                loginSpinner.classList.remove('hidden');
                loginButton.disabled = true;
            });
        }

        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                registerText.textContent = 'Creating Account...';
                registerSpinner.classList.remove('hidden');
                registerButton.disabled = true;
            });
        }

        // Nuclear option for back button prevention
        history.pushState(null, null, document.URL);
        window.addEventListener('popstate', function () {
            history.pushState(null, null, document.URL);
        });

        // Auto-adjust container height on load
        window.addEventListener('load', function() {
            const container = document.querySelector('.auto-height-container');
            const loginScroll = document.querySelector('#loginPage .scrollable-content');
            const registerScroll = document.querySelector('#registerPage .scrollable-content');
            
            // Set initial container height based on content
            if (loginScroll.scrollHeight > 600) {
                container.style.minHeight = loginScroll.scrollHeight + 100 + 'px';
            }
        });
    </script>   
</body>
</html>