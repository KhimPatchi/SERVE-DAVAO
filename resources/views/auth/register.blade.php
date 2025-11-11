<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ServeDavao | Register</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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
                        'fade-in': 'fadeIn 0.5s ease-out',
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
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#0f2027] via-[#203a43] to-[#2c5364] min-h-screen flex items-center justify-center p-4 font-inter">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl flex flex-col lg:flex-row overflow-hidden animate-fade-in">
        <!-- Left Side - Brand -->
        <div class="lg:w-1/2 bg-gradient-to-br from-primary-600 to-primary-800 p-8 text-white relative">
            <div class="relative z-10 h-full flex flex-col justify-center">
                <div class="mb-8">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm animate-float">
                        <i class="bi bi-person-plus-fill text-2xl text-white"></i>
                    </div>
                    <h1 class="text-4xl font-bold mb-4">Join ServeDavao</h1>
                    <p class="text-primary-100 text-lg">Start your volunteer journey today.</p>
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
                        <span class="text-primary-100">Make a difference in your community</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-6 h-6 bg-primary-400/30 rounded-full flex items-center justify-center mr-3">
                            <i class="bi bi-check-lg text-primary-200 text-sm"></i>
                        </div>
                        <span class="text-primary-100">Connect with fellow volunteers</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="lg:w-1/2 p-8 relative">
            <!-- Back Button -->
            <a href="{{ route('landing') }}" class="absolute top-6 left-6 w-10 h-10 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl flex items-center justify-center transition-all duration-300 hover:scale-105">
                <i class="bi bi-arrow-left"></i>
            </a>
            
            <div class="max-w-md mx-auto">
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
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input 
                                id="email" 
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
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <input 
                                    id="password" 
                                    type="password" 
                                    name="password" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 pr-12"
                                    placeholder="Create a password">
                                <span class="absolute right-3 top-3 text-gray-500 cursor-pointer hover:text-primary-600 transition-colors duration-200" id="togglePassword">
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

                        <!-- Submit Button -->
                        <button type="submit" id="registerButton" 
                                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                            <i class="bi bi-person-plus-fill mr-2"></i> 
                            <span id="registerText">Create Account</span>
                            <div id="registerSpinner" class="hidden animate-spin rounded-full h-5 w-5 border-b-2 border-white ml-2"></div>
                        </button>
                    </div>
                </form>

                <p class="text-center text-gray-600 text-sm mt-6">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-semibold">Sign in here</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        document.getElementById("togglePassword").onclick = function() {
            const input = document.getElementById("password");
            const icon = this.querySelector("i");
            input.type = input.type === "password" ? "text" : "password";
            icon.classList.toggle("bi-eye");
            icon.classList.toggle("bi-eye-slash");
        };

        document.getElementById("toggleConfirmPassword").onclick = function() {
            const input = document.getElementById("password_confirmation");
            const icon = this.querySelector("i");
            input.type = input.type === "password" ? "text" : "password";
            icon.classList.toggle("bi-eye");
            icon.classList.toggle("bi-eye-slash");
        };

        // Form submission animation
        const registerForm = document.getElementById('registerForm');
        const registerButton = document.getElementById('registerButton');
        const registerText = document.getElementById('registerText');
        const registerSpinner = document.getElementById('registerSpinner');

        if (registerForm) {
            registerForm.addEventListener('submit', function() {
                registerText.textContent = 'Creating Account...';
                registerSpinner.classList.remove('hidden');
                registerButton.disabled = true;
            });
        }
    </script>
</body>
</html>