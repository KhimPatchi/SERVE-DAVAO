<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="{{ asset('assets/img/logoDav.png') }}">
  <title>ServeDavao: Volunteer & Event Management</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8fafc;
    }

    .hero-bg {
      background-size: cover;
      background-position: center;
      position: relative;
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to bottom right, rgba(0, 0, 0, 0.55), rgba(26, 153, 136, 0.5));
    }

    /* Scroll animations */
    .scroll-fade-in {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.8s ease-out;
    }

    .scroll-fade-in.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .scroll-fade-in-left {
      opacity: 0;
      transform: translateX(-50px);
      transition: all 0.8s ease-out;
    }

    .scroll-fade-in-left.visible {
      opacity: 1;
      transform: translateX(0);
    }

    .scroll-fade-in-right {
      opacity: 0;
      transform: translateX(50px);
      transition: all 0.8s ease-out;
    }

    .scroll-fade-in-right.visible {
      opacity: 1;
      transform: translateX(0);
    }

    /* Staggered animations */
    .stagger-animate > * {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s ease-out;
    }

    .stagger-animate.visible > * {
      opacity: 1;
      transform: translateY(0);
    }

    .stagger-animate.visible > *:nth-child(1) { transition-delay: 0.1s; }
    .stagger-animate.visible > *:nth-child(2) { transition-delay: 0.2s; }
    .stagger-animate.visible > *:nth-child(3) { transition-delay: 0.3s; }
    .stagger-animate.visible > *:nth-child(4) { transition-delay: 0.4s; }
    .stagger-animate.visible > *:nth-child(5) { transition-delay: 0.5s; }

    /* Logo scroll animation */
    .logo-animate {
      transition: all 0.4s ease-in-out;
    }

    .logo-scrolled {
      transform: scale(0.9);
      opacity: 0.9;
    }

    /* Loading overlay */
    .loading-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      z-index: 9999;
      backdrop-filter: blur(4px);
    }

    .loading-spinner {
      border: 4px solid #f3f4f6;
      border-top: 4px solid #10b981;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Loading text animation */
    .loading-text {
      background: linear-gradient(90deg, #10b981, #34d399, #10b981);
      background-size: 200% 100%;
      background-clip: text;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
      0% { background-position: -200% 0; }
      100% { background-position: 200% 0; }
    }

    /* Button loading state */
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

    /* Enhanced event card styles */
    .event-card {
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .event-card:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .feature-card {
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .feature-card:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .line-clamp-2 {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .line-clamp-1 {
      display: -webkit-box;
      -webkit-line-clamp: 1;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    /* Smooth scrolling */
    html {
      scroll-behavior: smooth;
    }
    
    /* Contact form styles */
    .contact-input {
      transition: all 0.3s ease;
    }
    
    .contact-input:focus {
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
      border-color: #10b981;
    }
    
    .contact-card {
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .contact-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
  </style>
</head>

<body class="text-gray-800">

  <!-- Loading Overlay -->
  <div id="loadingOverlay" class="loading-overlay flex flex-col items-center justify-center">
    <div class="loading-spinner mb-4"></div>
    <div class="text-white text-lg font-semibold loading-text">Loading...</div>
    <p class="text-gray-300 text-sm mt-2">Please wait while we process your request</p>
  </div>

  <!-- Navbar -->
  <nav class="fixed w-full bg-white/90 backdrop-blur-md shadow-md z-50 transition-all duration-500">
    <div class="container mx-auto px-6 py-3 flex justify-between items-center logo-animate">
      <a href="#" class="flex items-center gap-3">
        <img src="/assets/img/logoDav.png" alt="ServeDavao Logo" class="w-10 h-10 rounded-full shadow transition-all duration-300 hover:scale-110">
        <span class="font-extrabold text-xl text-gray-800 transition-all duration-300 hover:text-emerald-600">
          Serve<span class="text-emerald-600">Davao</span>
        </span>
      </a>

      <ul class="hidden md:flex items-center gap-8 text-gray-700 font-medium">
        <li><a href="#home" class="hover:text-emerald-600 transition-all duration-300 hover:scale-105">Home</a></li>
        <li><a href="#about" class="hover:text-emerald-600 transition-all duration-300 hover:scale-105">About</a></li>
        <li><a href="#events" class="hover:text-emerald-600 transition-all duration-300 hover:scale-105">Events</a></li>
        <li><a href="#contact" class="hover:text-emerald-600 transition-all duration-300 hover:scale-105">Contact</a></li>
      </ul>

      <div class="hidden md:flex items-center gap-3">
        <a href="/login" class="text-sm font-semibold text-gray-700 hover:text-emerald-600 transition-all duration-300 transform hover:scale-105">Login</a>
        
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section id="home" class="hero-bg h-screen flex items-center justify-center relative" style="background-image: url('/assets/img/hero1.jpg');">
    <div class="hero-overlay"></div>
    <div class="text-center text-white z-10 px-6">
      <h1 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-lg scroll-fade-in">
        Empower Davao Through Volunteerism
      </h1>
      <p class="text-lg md:text-xl mb-6 max-w-2xl mx-auto opacity-90 scroll-fade-in" style="transition-delay: 0.2s">
        Join ServeDavao to make an impact in your community — connecting volunteers with meaningful events across the city.
      </p>
      <div class="scroll-fade-in" style="transition-delay: 0.4s">
        <a href="{{ auth()->check() ? route('events.index') : route('login') }}" 
           class="bg-emerald-600 hover:bg-emerald-700 px-6 py-3 rounded-lg font-semibold text-white shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl inline-block">
          {{ auth()->check() ? 'Explore Events' : 'Get Started' }}
        </a>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-20 bg-white">
    <div class="container mx-auto px-6 text-center">
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-6 scroll-fade-in">
        About <span class="text-emerald-600">ServeDavao</span>
      </h2>
      <p class="max-w-3xl mx-auto text-gray-600 text-lg leading-relaxed scroll-fade-in" style="transition-delay: 0.1s">
        ServeDavao is a platform that bridges volunteers and organizers for social good. 
        Volunteers can register, browse opportunities, and log their service hours — 
        while organizers post events, verify participation, and generate reports.
      </p>

      <div class="grid md:grid-cols-3 gap-8 mt-12 stagger-animate">
        <div class="bg-gray-50 rounded-xl p-8 shadow feature-card">
          <div class="text-emerald-600 text-4xl mb-4 transform transition-transform duration-300 hover:scale-110">
            <i class="bi bi-people-fill"></i>
          </div>
          <h4 class="text-xl font-semibold mb-2">Community Driven</h4>
          <p class="text-gray-600">We connect passionate individuals with causes that matter most to them.</p>
        </div>

        <div class="bg-gray-50 rounded-xl p-8 shadow feature-card">
          <div class="text-emerald-600 text-4xl mb-4 transform transition-transform duration-300 hover:scale-110">
            <i class="bi bi-calendar-event-fill"></i>
          </div>
          <h4 class="text-xl font-semibold mb-2">Event Management</h4>
          <p class="text-gray-600">Organizers can easily create, manage, and track volunteer activities.</p>
        </div>

        <div class="bg-gray-50 rounded-xl p-8 shadow feature-card">
          <div class="text-emerald-600 text-4xl mb-4 transform transition-transform duration-300 hover:scale-110">
            <i class="bi bi-shield-check"></i>
          </div>
          <h4 class="text-xl font-semibold mb-2">Secure Platform</h4>
          <p class="text-gray-600">All data is securely handled to protect volunteers and event organizers.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Events Section -->
  <section id="events" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-4 scroll-fade-in">
          Upcoming <span class="text-emerald-600">Events</span>
        </h2>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto scroll-fade-in" style="transition-delay: 0.1s">
          Discover real volunteer opportunities created by our community organizers
        </p>
      </div>

      @if(isset($events) && $events->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 stagger-animate">
          @foreach($events as $event)
          <div class="bg-white rounded-2xl shadow-lg overflow-hidden event-card group">
            <!-- Event Image -->
            <div class="h-48 bg-cover bg-center relative transition-transform duration-500 group-hover:scale-105" style="background-image: url('{{ $event->image ?? asset('assets/img/event-placeholder.jpg') }}');">
              <div class="absolute top-4 right-4">
                <span class="bg-emerald-500 text-white px-3 py-1 rounded-full text-xs font-medium transform transition-transform duration-300 group-hover:scale-110">
                  {{ $event->required_volunteers - $event->current_volunteers }} spots left
                </span>
              </div>
            </div>
            
            <!-- Event Content -->
            <div class="p-6">
              <!-- Date and Organizer -->
              <div class="flex items-center justify-between mb-3">
                <div class="flex items-center text-sm text-emerald-600 font-semibold">
                  <i class="bi bi-calendar-event mr-2 transition-transform duration-300 group-hover:scale-110"></i>
                  <span>{{ $event->date->format('M j, Y') }}</span>
                </div>
                <div class="text-xs text-gray-500 transform transition-all duration-300 group-hover:translate-x-1">
                  By {{ $event->organizer->name }}
                </div>
              </div>
              
              <!-- Event Title -->
              <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-emerald-600 transition-all duration-300">
                {{ $event->title }}
              </h3>
              
              <!-- Event Description -->
              <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed transition-all duration-300 group-hover:text-gray-700">
                {{ Str::limit($event->description, 100) }}
              </p>
              
              <!-- Location -->
              <div class="flex items-center text-sm text-gray-500 mb-4">
                <i class="bi bi-geo-alt mr-2 text-emerald-500 transition-transform duration-300 group-hover:scale-110"></i>
                <span class="line-clamp-1">{{ $event->location }}</span>
              </div>

              <!-- Volunteers Progress -->
              <div class="mb-4">
                <div class="flex justify-between items-center text-xs text-gray-500 mb-2">
                  <span>Volunteers</span>
                  <span>{{ $event->current_volunteers }}/{{ $event->required_volunteers }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                  <div class="bg-emerald-500 h-2 rounded-full transition-all duration-1000 ease-out" 
                       style="width: {{ ($event->current_volunteers / $event->required_volunteers) * 100 }}%">
                  </div>
                </div>
              </div>
              
              <!-- Action Button -->
              <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                <div class="flex items-center text-sm text-gray-500 transform transition-all duration-300 group-hover:translate-x-1">
                  <i class="bi bi-clock mr-1"></i>
                  <span>{{ $event->date->format('g:i A') }}</span>
                </div>
                <a href="{{ auth()->check() ? route('events.show', $event) : route('login') }}" 
                   class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center gap-1">
                  {{ auth()->check() ? 'View Details' : 'Login to View' }}
                  <i class="bi bi-arrow-right transition-transform duration-300 group-hover:translate-x-1"></i>
                </a>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <!-- View All Events Button -->
        <div class="text-center mt-12 scroll-fade-in">
          <a href="{{ auth()->check() ? route('events.index') : route('login') }}" 
             class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-xl inline-flex items-center gap-2">
            {{ auth()->check() ? 'View All Events' : 'Login to View Events' }}
            <i class="bi bi-arrow-right transition-transform duration-300 group-hover:translate-x-1"></i>
          </a>
        </div>

      @else
        <!-- Empty State -->
        <div class="text-center py-12 scroll-fade-in">
          <div class="bg-white rounded-2xl shadow-sm p-12 max-w-2xl mx-auto transform transition-all duration-500 hover:scale-105">
            <div class="mb-6 inline-flex rounded-2xl bg-emerald-50 p-6 transform transition-transform duration-300 hover:scale-110">
              <i class="bi bi-calendar-x text-4xl text-emerald-500"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-3">No Events Available Yet</h3>
            <p class="text-gray-600 mb-8 text-lg">Be the first to organize an event in your community and make a difference!</p>
            <a href="/register" 
               class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-xl inline-flex items-center gap-2">
              <i class="bi bi-plus-circle"></i>
              Become an Organizer
            </a>
          </div>
        </div>
      @endif
    </div>
  </section>

  <!-- Contact Us Section -->
  <section id="contact" class="py-20 bg-white">
    <div class="container mx-auto px-6">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-4 scroll-fade-in">
          Contact <span class="text-emerald-600">Us</span>
        </h2>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto scroll-fade-in" style="transition-delay: 0.1s">
          Have questions or want to get involved? We'd love to hear from you.
        </p>
      </div>

      <div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto stagger-animate">
        <!-- Contact Information -->
        <div class="space-y-8">
          <div class="bg-gray-50 rounded-2xl p-8 contact-card">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Get In Touch</h3>
            <p class="text-gray-600 mb-8">
              We're here to help you get involved with volunteering opportunities in Davao. 
              Whether you're an organizer or volunteer, reach out with any questions.
            </p>
            
            <div class="space-y-6">
              <div class="flex items-start gap-4">
                <div class="bg-emerald-100 p-3 rounded-full text-emerald-600 transform transition-transform duration-300 hover:scale-110">
                  <i class="bi bi-geo-alt-fill text-xl"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-800 mb-1">Our Location</h4>
                  <p class="text-gray-600">Davao City, Philippines</p>
                </div>
              </div>
              
              <div class="flex items-start gap-4">
                <div class="bg-emerald-100 p-3 rounded-full text-emerald-600 transform transition-transform duration-300 hover:scale-110">
                  <i class="bi bi-envelope-fill text-xl"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-800 mb-1">Email Us</h4>
                  <p class="text-gray-600">contact@servedavao.org</p>
                </div>
              </div>
              
              <div class="flex items-start gap-4">
                <div class="bg-emerald-100 p-3 rounded-full text-emerald-600 transform transition-transform duration-300 hover:scale-110">
                  <i class="bi bi-telephone-fill text-xl"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-800 mb-1">Call Us</h4>
                  <p class="text-gray-600">+63 82 123 4567</p>
                </div>
              </div>
              
              <div class="flex items-start gap-4">
                <div class="bg-emerald-100 p-3 rounded-full text-emerald-600 transform transition-transform duration-300 hover:scale-110">
                  <i class="bi bi-clock-fill text-xl"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-800 mb-1">Response Time</h4>
                  <p class="text-gray-600">We typically respond within 24 hours</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- FAQ Section -->
          <div class="bg-emerald-50 rounded-2xl p-8 contact-card">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Frequently Asked Questions</h3>
            <div class="space-y-4">
              <div class="border-b border-emerald-100 pb-4">
                <h4 class="font-semibold text-gray-800 mb-2">How do I become a volunteer?</h4>
                <p class="text-gray-600 text-sm">Simply create an account, browse available events, and sign up for those that interest you.</p>
              </div>
              <div class="border-b border-emerald-100 pb-4">
                <h4 class="font-semibold text-gray-800 mb-2">Can organizations post events?</h4>
                <p class="text-gray-600 text-sm">Yes! Register as an organization to create and manage volunteer events.</p>
              </div>
              <div>
                <h4 class="font-semibold text-gray-800 mb-2">Is there a cost to use ServeDavao?</h4>
                <p class="text-gray-600 text-sm">No, our platform is completely free for both volunteers and organizers.</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Contact Form -->
        <div class="bg-gray-50 rounded-2xl p-8 contact-card">
          <h3 class="text-2xl font-bold text-gray-800 mb-6">Send Us a Message</h3>
          <form id="contactForm" class="space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                <input type="text" id="firstName" name="firstName" required 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none contact-input"
                       placeholder="Your first name">
              </div>
              <div>
                <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                <input type="text" id="lastName" name="lastName" required 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none contact-input"
                       placeholder="Your last name">
              </div>
            </div>
            
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
              <input type="email" id="email" name="email" required 
                     class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none contact-input"
                     placeholder="your.email@example.com">
            </div>
            
            <div>
              <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
              <select id="subject" name="subject" required 
                      class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none contact-input">
                <option value="" disabled selected>Select a subject</option>
                <option value="volunteer">Volunteer Inquiry</option>
                <option value="organizer">Organizer Inquiry</option>
                <option value="partnership">Partnership Opportunity</option>
                <option value="technical">Technical Support</option>
                <option value="other">Other</option>
              </select>
            </div>
            
            <div>
              <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
              <textarea id="message" name="message" rows="5" required 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none contact-input resize-none"
                        placeholder="Tell us how we can help you..."></textarea>
            </div>
            
            <button type="submit" 
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center justify-center gap-2">
              <span>Send Message</span>
              <i class="bi bi-send"></i>
            </button>
          </form>
          
          <!-- Success Message (hidden by default) -->
          <div id="successMessage" class="hidden mt-6 p-4 bg-emerald-100 border border-emerald-400 text-emerald-700 rounded-lg">
            <div class="flex items-center gap-2">
              <i class="bi bi-check-circle-fill text-emerald-600"></i>
              <p class="font-medium">Thank you! Your message has been sent successfully.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-400 py-10">
    <div class="container mx-auto px-6 text-center">
      <div class="flex justify-center items-center mb-6 scroll-fade-in">
        <img src="/assets/img/logoDav.png" class="w-10 h-10 rounded-full mr-3 transform transition-transform duration-300 hover:scale-110">
        <h3 class="text-white text-2xl font-extrabold transform transition-all duration-300 hover:text-emerald-500">
          Serve<span class="text-emerald-500">Davao</span>
        </h3>
      </div>

      <p class="text-gray-400 mb-6 max-w-xl mx-auto scroll-fade-in" style="transition-delay: 0.1s">
        Empowering communities through service, one event at a time.
      </p>

      <div class="flex justify-center gap-5 text-xl stagger-animate">
        <a href="#" class="hover:text-emerald-500 transition-all duration-300 transform hover:scale-125"><i class="bi bi-facebook"></i></a>
        <a href="#" class="hover:text-emerald-500 transition-all duration-300 transform hover:scale-125"><i class="bi bi-twitter"></i></a>
        <a href="#" class="hover:text-emerald-500 transition-all duration-300 transform hover:scale-125"><i class="bi bi-instagram"></i></a>
      </div>

      <p class="text-gray-500 text-sm mt-6 scroll-fade-in" style="transition-delay: 0.2s">&copy; 2025 ServeDavao. All rights reserved.</p>
    </div>
  </footer>

   <!-- Enhanced JavaScript with Scroll Animations -->
  <script>
    // Loading animation function - FIXED
    function showLoading(event = null) {
        if (event) {
            const href = event.currentTarget.getAttribute('href');
            
            // ALLOW smooth scrolling to sections without loading
            if (href && href.startsWith('#')) {
                return; // Don't show loading for section links
            }
            
            // For external links or page navigation, show loading
            if (href && (href.startsWith('/') || href.startsWith('http'))) {
                event.preventDefault();
            } else {
                return; // Don't show loading for other cases
            }
        }

        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.style.display = 'flex';
        
        if (event && event.currentTarget.tagName === 'A') {
            const href = event.currentTarget.getAttribute('href');
            if (href && (href.startsWith('/') || href.startsWith('http'))) {
                setTimeout(() => {
                    window.location.href = href;
                }, 800); // Increased timeout to ensure loading is visible
            }
        }
    }

    function hideLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.style.display = 'none';
    }

    // NEW: Handle browser back/forward buttons
    window.addEventListener('pageshow', function(event) {
        // If page is loaded from cache (back/forward navigation), hide loading
        if (event.persisted) {
            hideLoading();
        }
    });

    // NEW: Also hide loading when page becomes visible again
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // Small delay to ensure page is fully loaded
            setTimeout(hideLoading, 100);
        }
    });

    // NEW: Safety timeout to hide loading after 3 seconds (in case something goes wrong)
    function setupSafetyTimeout() {
        setTimeout(() => {
            hideLoading();
        }, 3000);
    }

    // Smooth scrolling for navigation links
    function initSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    // Smooth scroll to target
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // Scroll animation observer
    function initScrollAnimations() {
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

        // Observe all elements with scroll animation classes
        document.querySelectorAll('.scroll-fade-in, .scroll-fade-in-left, .scroll-fade-in-right, .stagger-animate').forEach(el => {
            observer.observe(el);
        });
    }

    // Enhanced hover effects
    function initHoverEffects() {
        // Add hover effects to all interactive elements
        const hoverElements = document.querySelectorAll('a, button, .feature-card, .event-card, .contact-card');
        hoverElements.forEach(el => {
            el.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            el.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    }

    // Contact form handling
    function initContactForm() {
      const contactForm = document.getElementById('contactForm');
      const successMessage = document.getElementById('successMessage');
      
      if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          // Show loading state
          const submitButton = contactForm.querySelector('button[type="submit"]');
          const originalText = submitButton.innerHTML;
          submitButton.innerHTML = '<span>Sending...</span>';
          submitButton.disabled = true;
          
          // Simulate form submission (replace with actual form submission)
          setTimeout(() => {
            // Reset form
            contactForm.reset();
            
            // Show success message
            successMessage.classList.remove('hidden');
            
            // Reset button
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
            
            // Hide success message after 5 seconds
            setTimeout(() => {
              successMessage.classList.add('hidden');
            }, 5000);
          }, 1500);
        });
      }
    }

    // Initialize everything when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize smooth scrolling FIRST
        initSmoothScrolling();
        
        // Initialize scroll animations
        initScrollAnimations();
        
        // Initialize hover effects
        initHoverEffects();
        
        // Initialize contact form
        initContactForm();

        // Only attach loading to specific action buttons/links
        const loadingTriggers = document.querySelectorAll(
            'a[href*="/login"], a[href*="/register"], a[href*="/events"], a.bg-emerald-600'
        );
        
        loadingTriggers.forEach(element => {
            if (element.tagName === 'A') {
                // Don't attach loading to section links
                const href = element.getAttribute('href');
                if (!href || !href.startsWith('#')) {
                    element.addEventListener('click', function(e) {
                        showLoading(e);
                        // Setup safety timeout when loading is shown
                        setupSafetyTimeout();
                    });
                }
            }
        });

        // Handle page load - ensure loading is hidden
        hideLoading();
        
        // Initial safety timeout setup
        setupSafetyTimeout();
    });

    // Logo scroll animation
    window.addEventListener("scroll", () => {
        const logo = document.querySelector(".logo-animate");
        const scrollY = window.scrollY;
        if (scrollY > 50) {
            logo.classList.add("logo-scrolled");
        } else {
            logo.classList.remove("logo-scrolled");
        }
    });

    // NEW: Also hide loading when the page is about to be unloaded (beforeunload)
    window.addEventListener('beforeunload', function() {
        hideLoading();
    });

    // NEW: Hide loading when the page gains focus (user returns to tab)
    window.addEventListener('focus', function() {
        hideLoading();
    });

    // NEW: Handle popstate event (back/forward navigation)
    window.addEventListener('popstate', function() {
        hideLoading();
    });

    // NEW: Global click handler to catch any missed navigation
    document.addEventListener('click', function(e) {
        const target = e.target.closest('a');
        if (target && target.getAttribute('href') && 
            !target.getAttribute('href').startsWith('#') &&
            (target.getAttribute('href').startsWith('/') || target.getAttribute('href').startsWith('http'))) {
            setupSafetyTimeout();
        }
    });
  </script>
</body>
</html> 