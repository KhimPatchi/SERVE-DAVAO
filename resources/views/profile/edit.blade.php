@extends('layouts.sidebar.sidebar')

@section('content')
<div class="p-4 md:p-6 bg-gradient-to-br from-gray-50 to-emerald-50/30 min-h-screen">
  
  <!-- Enhanced Header -->
  <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-6">
    <div class="flex-1">
      <div class="inline-flex items-center gap-3 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-2xl shadow-sm border border-emerald-100 mb-4 transform transition-all duration-300 hover:scale-105">
        <div class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></div>
        <span class="text-sm font-medium text-emerald-700">Profile Management</span>
      </div>
      <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-3 scroll-fade-in">
        Edit Your 
        <span class="bg-gradient-to-r from-emerald-600 to-green-500 bg-clip-text text-transparent">
          Profile
        </span>
      </h1>
      <p class="text-lg text-gray-600 max-w-2xl scroll-fade-in" style="transition-delay: 0.1s">
        Manage your personal information, security settings, and account preferences in one place.
      </p>
    </div>
    
    <!-- User Profile Badge -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-emerald-100 p-4 transform transition-all duration-500 hover:scale-105 hover:shadow-xl">
      <div class="flex items-center gap-4">
        <div class="relative">
          <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-green-500 rounded-2xl flex items-center justify-center shadow-lg">
            <span class="text-white font-bold text-lg">
              {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </span>
          </div>
          <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-400 border-2 border-white rounded-full flex items-center justify-center">
            <i class="bi bi-pencil-fill text-white text-xs"></i>
          </div>
        </div>
        <div class="flex-1 min-w-0">
          <h3 class="font-bold text-gray-900 text-lg truncate">{{ Auth::user()->name }}</h3>
          <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
          <div class="flex items-center gap-2 mt-1">
            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-medium px-2 py-1 rounded-full">
              <i class="bi bi-star-fill"></i>
              Active Member
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Enhanced Messages -->
  @if(session('success'))
    <div class="mb-8 bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl flex items-center gap-4 shadow-lg scroll-fade-in" role="alert">
      <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
        <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
      </div>
      <div class="flex-1">
        <strong class="font-bold text-lg">Success!</strong>
        <p class="mt-1">{{ session('success') }}</p>
      </div>
      <button class="text-emerald-600 hover:text-emerald-800 transition-colors">
        <i class="bi bi-x-lg text-lg"></i>
      </button>
    </div>
  @endif

  @if($errors->any())
    <div class="mb-8 bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl shadow-lg scroll-fade-in" role="alert">
      <div class="flex items-center gap-4">
        <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
          <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl"></i>
        </div>
        <div class="flex-1">
          <strong class="font-bold text-lg">Please check your input:</strong>
          <ul class="mt-2 space-y-1">
            @foreach($errors->all() as $error)
              <li class="flex items-center gap-2">
                <i class="bi bi-dot text-lg"></i>
                {{ $error }}
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  @endif

  <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
    <!-- Navigation Sidebar -->
    <div class="xl:col-span-1 space-y-6">
      <!-- Progress Card -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 feature-card">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
          <i class="bi bi-graph-up text-emerald-600"></i>
          Profile Completion
        </h3>
        <div class="space-y-4">
          <div class="flex justify-between items-center">
            <span class="text-sm font-medium text-gray-700">Basic Info</span>
            <span class="text-sm font-bold text-emerald-600">100%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-emerald-500 h-2 rounded-full transition-all duration-1000" style="width: 100%"></div>
          </div>
          
          <div class="flex justify-between items-center">
            <span class="text-sm font-medium text-gray-700">Contact Details</span>
            <span class="text-sm font-bold text-emerald-600">80%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-emerald-500 h-2 rounded-full transition-all duration-1000" style="width: 80%"></div>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="bg-gradient-to-br from-emerald-500 to-green-500 rounded-2xl shadow-lg p-6 text-white feature-card">
        <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
          <i class="bi bi-activity"></i>
          Account Stats
        </h3>
        <div class="space-y-4">
          <div class="flex justify-between items-center">
            <span class="text-emerald-100">Member Since</span>
            <span class="font-semibold">{{ $user->created_at->format('M Y') }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-emerald-100">Events Joined</span>
            <span class="font-semibold">12</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-emerald-100">Hours Volunteered</span>
            <span class="font-semibold">48h</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="xl:col-span-3 space-y-8">
      <!-- Personal Information Card -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden feature-card">
        <div class="bg-gradient-to-r from-emerald-500 to-green-500 px-6 py-5">
          <h2 class="text-xl font-bold text-white flex items-center gap-3">
            <i class="bi bi-person-badge text-white text-2xl"></i>
            Personal Information
          </h2>
          <p class="text-emerald-100 mt-1">Update your basic profile details and contact information</p>
        </div>
        <div class="p-6">
          <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Name Field -->
              <div class="group">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                  <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center group-focus-within:bg-emerald-200 transition-colors">
                    <i class="bi bi-person text-emerald-600"></i>
                  </div>
                  Full Name *
                </label>
                <input id="name" type="text" 
                      class="block w-full border-2 border-gray-200 rounded-xl shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 px-4 py-3 transition-all duration-300 @error('name') border-red-300 bg-red-50 @enderror" 
                      name="name" value="{{ old('name', $user->name) }}" required
                      placeholder="Enter your full name">
                @error('name')
                  <p class="mt-2 text-sm text-red-600 flex items-center gap-2">
                    <i class="bi bi-exclamation-circle"></i>
                    {{ $message }}
                  </p>
                @enderror
              </div>

              <!-- Email Field -->
              <div class="group">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                  <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center group-focus-within:bg-emerald-200 transition-colors">
                    <i class="bi bi-envelope text-emerald-600"></i>
                  </div>
                  Email Address *
                </label>
                <input id="email" type="email" 
                      class="block w-full border-2 border-gray-200 rounded-xl shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 px-4 py-3 transition-all duration-300 @error('email') border-red-300 bg-red-50 @enderror" 
                      name="email" value="{{ old('email', $user->email) }}" required
                      placeholder="Enter your email address">
                @error('email')
                  <p class="mt-2 text-sm text-red-600 flex items-center gap-2">
                    <i class="bi bi-exclamation-circle"></i>
                    {{ $message }}
                  </p>
                @enderror
              </div>

              <!-- Phone Field -->
              <div class="group">
                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                  <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center group-focus-within:bg-emerald-200 transition-colors">
                    <i class="bi bi-telephone text-emerald-600"></i>
                  </div>
                  Phone Number
                </label>
                <input id="phone" type="tel" 
                      class="block w-full border-2 border-gray-200 rounded-xl shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 px-4 py-3 transition-all duration-300" 
                      name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                      placeholder="+1 (555) 123-4567">
              </div>

              <!-- Location Field -->
              <div class="group">
                <label for="location" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                  <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center group-focus-within:bg-emerald-200 transition-colors">
                    <i class="bi bi-geo-alt text-emerald-600"></i>
                  </div>
                  Location
                </label>
                <input id="location" type="text" 
                      class="block w-full border-2 border-gray-200 rounded-xl shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 px-4 py-3 transition-all duration-300" 
                      name="location" value="{{ old('location', $user->location ?? '') }}"
                      placeholder="Your city or region">
              </div>
            </div>

            <!-- Bio Field -->
            <div class="group">
              <label for="bio" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center group-focus-within:bg-emerald-200 transition-colors">
                  <i class="bi bi-file-text text-emerald-600"></i>
                </div>
                Bio
              </label>
              <textarea id="bio" name="bio" rows="4" 
                       class="block w-full border-2 border-gray-200 rounded-xl shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 px-4 py-3 transition-all duration-300 resize-none"
                       placeholder="Tell us about yourself, your interests, and what motivates you to volunteer...">{{ old('bio', $user->bio ?? '') }}</textarea>
              <div class="flex justify-between items-center mt-2">
                <p class="text-sm text-gray-500 flex items-center gap-1">
                  <i class="bi bi-info-circle"></i>
                  Share your story with the community
                </p>
                <span id="bioCounter" class="text-xs text-gray-400">0/500</span>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
              <div class="text-sm text-gray-500 flex items-center gap-2">
                <i class="bi bi-shield-check text-emerald-500"></i>
                <span>Your information is secure and encrypted</span>
              </div>
              <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <a href="{{ route('dashboard') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center justify-center gap-2 order-2 sm:order-1">
                  <i class="bi bi-arrow-left"></i>
                  Back to Dashboard
                </a>
                <button type="submit" 
                        class="bg-gradient-to-r from-emerald-600 to-green-500 hover:from-emerald-700 hover:to-green-600 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-xl flex items-center justify-center gap-2 order-1 sm:order-2 shadow-lg">
                  <i class="bi bi-check-lg"></i>
                  Save Changes
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Security Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Password Update Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden feature-card">
          <div class="bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-5">
            <h2 class="text-xl font-bold text-white flex items-center gap-3">
              <i class="bi bi-shield-lock text-white text-2xl"></i>
              Change Password
            </h2>
            <p class="text-orange-100 mt-1">Keep your account secure with a strong password</p>
          </div>
          <div class="p-6">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
              @csrf
              @method('PUT')

              <div class="space-y-4">
                <div class="group">
                  <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center group-focus-within:bg-orange-200 transition-colors">
                      <i class="bi bi-key text-orange-600"></i>
                    </div>
                    Current Password
                  </label>
                  <input id="current_password" type="password" 
                        class="block w-full border-2 border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 px-4 py-3 transition-all duration-300" 
                        name="current_password" 
                        placeholder="Enter current password">
                </div>

                <div class="grid grid-cols-1 gap-4">
                  <div class="group">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                      <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center group-focus-within:bg-emerald-200 transition-colors">
                        <i class="bi bi-key-fill text-emerald-600"></i>
                      </div>
                      New Password
                    </label>
                    <input id="password" type="password" 
                          class="block w-full border-2 border-gray-200 rounded-xl shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 px-4 py-3 transition-all duration-300" 
                          name="password" 
                          placeholder="Enter new password">
                  </div>

                  <div class="group">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                      <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center group-focus-within:bg-emerald-200 transition-colors">
                        <i class="bi bi-key-fill text-emerald-600"></i>
                      </div>
                      Confirm Password
                    </label>
                    <input id="password_confirmation" type="password" 
                          class="block w-full border-2 border-gray-200 rounded-xl shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 px-4 py-3 transition-all duration-300" 
                          name="password_confirmation" 
                          placeholder="Confirm new password">
                  </div>
                </div>
              </div>

              <div class="pt-4 border-t border-gray-200">
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-orange-600 to-amber-500 hover:from-orange-700 hover:to-amber-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center justify-center gap-2 shadow-md">
                  <i class="bi bi-arrow-repeat"></i>
                  Update Password
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Account Status Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden feature-card">
          <div class="bg-gradient-to-r from-purple-500 to-indigo-500 px-6 py-5">
            <h2 class="text-xl font-bold text-white flex items-center gap-3">
              <i class="bi bi-person-check text-white text-2xl"></i>
              Account Status
            </h2>
            <p class="text-purple-100 mt-1">Your account information and verification status</p>
          </div>
          <div class="p-6">
            <div class="space-y-4">
              <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-envelope-check text-emerald-600"></i>
                  </div>
                  <div>
                    <span class="block text-sm font-semibold text-gray-700">Email Verified</span>
                    <span class="text-xs text-gray-500">Account security</span>
                  </div>
                </div>
                @if($user->hasVerifiedEmail())
                  <span class="bg-emerald-100 text-emerald-800 text-sm font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                    <i class="bi bi-check-circle-fill"></i>
                    Verified
                  </span>
                @else
                  <span class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                    <i class="bi bi-clock"></i>
                    Pending
                  </span>
                @endif
              </div>

              <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-calendar-check text-blue-600"></i>
                  </div>
                  <div>
                    <span class="block text-sm font-semibold text-gray-700">Member Since</span>
                    <span class="text-xs text-gray-500">Community member</span>
                  </div>
                </div>
                <span class="text-sm font-semibold text-gray-900">
                  {{ $user->created_at->format('M j, Y') }}
                </span>
              </div>

              @if($user->isVerifiedOrganizer())
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                      <i class="bi bi-patch-check text-purple-600"></i>
                    </div>
                    <div>
                      <span class="block text-sm font-semibold text-gray-700">Organizer Status</span>
                      <span class="text-xs text-gray-500">Event creation</span>
                    </div>
                  </div>
                  <span class="bg-purple-100 text-purple-800 text-sm font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                    <i class="bi bi-star-fill"></i>
                    Verified
                  </span>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Enhanced animations and styles */
  .scroll-fade-in {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .scroll-fade-in.visible {
    opacity: 1;
    transform: translateY(0);
  }

  .feature-card {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  }

  /* Enhanced input focus states */
  .group:focus-within .group-focus-within\:bg-emerald-200 {
    background-color: #a7f3d0;
  }

  input:focus, textarea:focus {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.15);
  }

  /* Gradient text animation */
  .bg-gradient-text {
    background: linear-gradient(135deg, #10b981, #34d399, #10b981);
    background-size: 200% 200%;
    animation: gradientShift 3s ease infinite;
  }

  @keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  /* Loading animation */
  .button-loading {
    position: relative;
    color: transparent;
    pointer-events: none;
  }

  .button-loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin-left: -10px;
    margin-top: -10px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 0.8s ease infinite;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Scroll animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
        }
      });
    }, observerOptions);

    document.querySelectorAll('.scroll-fade-in').forEach(el => {
      observer.observe(el);
    });

    // Bio character counter
    const bioTextarea = document.getElementById('bio');
    const bioCounter = document.getElementById('bioCounter');
    
    if (bioTextarea && bioCounter) {
      function updateBioCounter() {
        const length = bioTextarea.value.length;
        bioCounter.textContent = `${length}/500`;
        
        if (length > 450) {
          bioCounter.classList.add('text-orange-500', 'font-semibold');
        } else if (length > 500) {
          bioCounter.classList.add('text-red-500', 'font-semibold');
        } else {
          bioCounter.classList.remove('text-orange-500', 'text-red-500', 'font-semibold');
        }
      }
      
      bioTextarea.addEventListener('input', updateBioCounter);
      updateBioCounter();
    }

    // Enhanced form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
      form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
          const originalText = submitBtn.innerHTML;
          submitBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin mr-2"></i> Processing...';
          submitBtn.disabled = true;
          submitBtn.classList.add('button-loading');
          
          // Revert after 3 seconds (for demo) - remove in production
          setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            submitBtn.classList.remove('button-loading');
          }, 3000);
        }
      });
    });

    // Add hover effects to all interactive elements
    const interactiveElements = document.querySelectorAll('.feature-card, button, a.bg-gradient-to-r');
    interactiveElements.forEach(el => {
      el.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px) scale(1.02)';
      });
      el.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });

    // Password strength indicator (optional enhancement)
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
      passwordInput.addEventListener('input', function() {
        // Add password strength logic here if needed
      });
    }
  });
</script>
@endsection