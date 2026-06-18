<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="{{ asset('assets/img/logoDav.png') }}">
  <title>ServeDavao: Volunteer & Event Management</title>

  <!-- Tailwind CSS -->
  <!-- Tailwind CSS -->
  <!-- Tailwind CSS -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
  
  <!-- Google reCAPTCHA -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <style>
    /* Custom styles moved to resources/css/app.css */

    /* ===== FAQ CHATBOT STYLES ===== */
    #faq-chatbot {
      display: flex;
      flex-direction: column;
      height: 420px;
      border-radius: 1rem;
      overflow: hidden;
      box-shadow: 0 8px 32px rgba(5, 150, 105, 0.15);
      background: #ffffff;
    }
    #faq-chatbot-header {
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
      padding: 1rem 1.25rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      flex-shrink: 0;
    }
    #faq-chatbot-header .bot-avatar {
      width: 40px;
      height: 40px;
      background: rgba(255,255,255,0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      color: #fff;
      flex-shrink: 0;
    }
    #faq-chatbot-header .bot-info h4 {
      color: #fff;
      font-weight: 700;
      font-size: 0.95rem;
      margin: 0;
    }
    #faq-chatbot-header .bot-info p {
      color: rgba(255,255,255,0.8);
      font-size: 0.75rem;
      margin: 0;
    }
    #faq-chatbot-header .online-dot {
      width: 8px;
      height: 8px;
      background: #6ee7b7;
      border-radius: 50%;
      animation: pulse-dot 1.8s infinite;
      margin-left: auto;
    }
    @keyframes pulse-dot {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.5; transform: scale(1.3); }
    }
    #faq-messages {
      flex: 1;
      overflow-y: auto;
      padding: 1rem;
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
      background: #f0fdf4;
    }
    #faq-messages::-webkit-scrollbar { width: 4px; }
    #faq-messages::-webkit-scrollbar-thumb { background: #a7f3d0; border-radius: 99px; }
    .faq-msg {
      display: flex;
      gap: 0.5rem;
      align-items: flex-end;
      animation: msg-in 0.25s ease;
    }
    @keyframes msg-in {
      from { opacity: 0; transform: translateY(8px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .faq-msg.bot { flex-direction: row; }
    .faq-msg.user { flex-direction: row-reverse; }
    .faq-msg .msg-avatar {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: #059669;
      color: #fff;
      font-size: 0.7rem;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .faq-msg.user .msg-avatar { background: #6b7280; }
    .faq-bubble {
      max-width: 82%;
      padding: 0.6rem 0.9rem;
      border-radius: 1.1rem;
      font-size: 0.85rem;
      line-height: 1.5;
    }
    .faq-msg.bot .faq-bubble {
      background: #fff;
      color: #1f2937;
      border-bottom-left-radius: 4px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    }
    .faq-msg.user .faq-bubble {
      background: #059669;
      color: #fff;
      border-bottom-right-radius: 4px;
      box-shadow: 0 2px 8px rgba(5,150,105,0.25);
    }
    #faq-quick-btns {
      padding: 0.5rem 1rem 0;
      display: flex;
      flex-wrap: wrap;
      gap: 0.4rem;
      background: #f0fdf4;
      flex-shrink: 0;
    }
    .faq-quick-btn {
      background: #d1fae5;
      color: #065f46;
      border: 1px solid #a7f3d0;
      border-radius: 999px;
      padding: 0.3rem 0.75rem;
      font-size: 0.75rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s;
    }
    .faq-quick-btn:hover {
      background: #059669;
      color: #fff;
      border-color: #059669;
      transform: translateY(-1px);
    }
    #faq-input-area {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1rem;
      background: #fff;
      border-top: 1px solid #d1fae5;
      flex-shrink: 0;
    }
    #faq-input {
      flex: 1;
      border: 1.5px solid #d1fae5;
      border-radius: 999px;
      padding: 0.5rem 1rem;
      font-size: 0.85rem;
      outline: none;
      transition: border-color 0.2s;
      background: #f0fdf4;
      color: #1f2937;
    }
    #faq-input:focus { border-color: #059669; background: #fff; }
    #faq-send-btn {
      width: 36px;
      height: 36px;
      background: #059669;
      border: none;
      border-radius: 50%;
      color: #fff;
      font-size: 0.9rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
      flex-shrink: 0;
    }
    #faq-send-btn:hover { background: #047857; transform: scale(1.1); }
    .typing-indicator { display: flex; align-items: center; gap: 4px; padding: 0.4rem 0.2rem; }
    .typing-dot {
      width: 7px; height: 7px; background: #059669; border-radius: 50%;
      animation: typing 1.2s infinite;
    }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typing {
      0%, 60%, 100% { transform: translateY(0); opacity: 0.5; }
      30% { transform: translateY(-5px); opacity: 1; }
    }

    /* ---- Follow-up / action buttons ---- */
    .faq-action-btns {
      display: flex;
      flex-wrap: wrap;
      gap: 0.4rem;
      margin-top: 0.55rem;
    }
    .faq-action-btn {
      border: none;
      border-radius: 999px;
      padding: 0.4rem 1.1rem;
      font-size: 0.78rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      letter-spacing: 0.01em;
    }
    .faq-action-btn.yes  { background: #059669; color: #fff; }
    .faq-action-btn.yes:hover  { background: #047857; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(5,150,105,0.3); }
    .faq-action-btn.no   { background: #e5e7eb; color: #374151; }
    .faq-action-btn.no:hover   { background: #d1d5db; transform: translateY(-2px); }
    .faq-action-btn.restart { background: linear-gradient(135deg,#059669,#047857); color:#fff; width:100%; justify-content:center; display:flex; align-items:center; gap:0.4rem; }
    .faq-action-btn.restart:hover { transform:translateY(-2px); box-shadow:0 4px 14px rgba(5,150,105,0.35); }
    .faq-action-btn:disabled { opacity: 0.45; cursor: not-allowed; transform: none !important; box-shadow: none !important; }
    /* Locked input state */
    #faq-chatbot.input-locked #faq-input  { opacity: 0.45; pointer-events: none; }
    #faq-chatbot.input-locked #faq-send-btn { opacity: 0.45; pointer-events: none; }
    /* Step label */
    .faq-step-label {
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.07em;
      color: #059669;
      margin-bottom: 0.35rem;
      opacity: 0.8;
    }
  </style>
</head>

<body class="text-gray-800">

  <!-- Professional & Aesthetic Intro Loading -->
  <div id="intro-loading" class="fixed inset-0 flex flex-col items-center justify-center overflow-hidden" 
       style="z-index: 999999; background-color: #ffffff; transition: transform 0.8s cubic-bezier(0.85, 0, 0.15, 1), opacity 0.8s ease;">
       
      <!-- Elegant Subtle Background -->
      <div class="absolute inset-0 z-0 opacity-30 pointer-events-none fade-in-anim" style="background: radial-gradient(circle at center, #d1fae5 0%, #ffffff 100%);"></div>

      <!-- Main Content Container -->
      <div class="relative z-10 flex flex-col items-center justify-center scale-up-subtle">
          <!-- Pristine Logo Presentation -->
          <div class="mb-8 relative flex justify-center items-center">
              <!-- Extremely subtle glowing aura behind logo -->
              <div class="absolute inset-0 opacity-20 filter blur-2xl rounded-full animate-pulse-slow" style="background-color: #34d399;"></div>
              
              <!-- Clean Logo -->
              <img src="{{ asset('assets/img/logoDav.png') }}" alt="ServeDavao Logo" class="relative z-10 w-28 h-28 object-contain drop-shadow-xl" style="animation: float-gentle 4s ease-in-out infinite;">
          </div>
          
          <!-- Modern Typography -->
          <div class="flex flex-col items-center overflow-hidden">
              <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-2 reveal-text-up" style="color: #1f2937;">
                  Serve<span class="text-emerald-600">Davao</span>
              </h2>
          </div>
          <div class="overflow-hidden mb-12">
              <p class="text-[0.7rem] md:text-xs font-semibold tracking-[0.3em] uppercase reveal-text-up delay-200" style="color: #6b7280;">
                  Empowering Volunteers
              </p>
          </div>
          
          <!-- Ultra-Minimalist Loader Line -->
          <div class="w-48 max-w-full rounded-full overflow-hidden reveal-fade delay-400" style="height: 3px; background-color: #e5e7eb;">
              <div class="h-full bg-emerald-500 rounded-full loading-line"></div>
          </div>
      </div>
  </div>

  <style>
      .scale-up-subtle { animation: scaleUp 1s cubic-bezier(0.16, 1, 0.3, 1) both; }
      @keyframes scaleUp {
          0% { transform: scale(0.95); opacity: 0; }
          100% { transform: scale(1); opacity: 1; }
      }

      .float-gentle { animation: floatGentle 4s ease-in-out infinite; }
      @keyframes floatGentle {
          0%, 100% { transform: translateY(0); }
          50% { transform: translateY(-8px); }
      }

      .fade-in-anim { animation: fadeIn 1.5s ease both; }
      @keyframes fadeIn {
          0% { opacity: 0; }
          100% { opacity: 0.3; }
      }

      .reveal-text-up { animation: revealTextUp 0.8s cubic-bezier(0.77, 0, 0.175, 1) both; }
      @keyframes revealTextUp {
          0% { transform: translateY(100%); opacity: 0; }
          100% { transform: translateY(0); opacity: 1; }
      }

      .reveal-fade { animation: revealFade 0.8s ease both; }
      @keyframes revealFade {
          0% { opacity: 0; transform: translateY(10px); }
          100% { opacity: 1; transform: translateY(0); }
      }

      /* Slow pulse for the aura */
      .animate-pulse-slow { animation: pulseSlow 4s ease-in-out infinite; }
      @keyframes pulseSlow {
          0%, 100% { opacity: 0.2; transform: scale(1); }
          50% { opacity: 0.4; transform: scale(1.1); }
      }

      .delay-200 { animation-delay: 0.2s; }
      .delay-400 { animation-delay: 0.4s; }

      .loading-line {
          width: 0%;
          animation: loadLine 1.6s cubic-bezier(0.65, 0, 0.35, 1) forwards;
      }
      @keyframes loadLine {
          0% { width: 0%; }
          40% { width: 50%; }
          100% { width: 100%; }
      }
      
      .intro-slide-up {
          transform: translateY(-100%);
          pointer-events: none;
      }
  </style>

  <script>
      window.addEventListener('load', function() {
          setTimeout(() => {
              const loader = document.getElementById('intro-loading');
              if(loader) {
                  loader.classList.add('intro-slide-up');
                  setTimeout(() => {
                      loader.remove();
                  }, 850); 
              }
          }, 1800); // slightly longer to let animations breathe
      });
  </script>


  <!-- Navbar -->
  <nav class="fixed w-full bg-white/90 backdrop-blur-md shadow-md z-50 transition-all duration-500">
    <div class="container mx-auto px-6 py-3 flex justify-between items-center logo-animate">
      <a href="#" class="flex items-center gap-3">
        <img src="/assets/img/logoDav.png" alt="ServeDavao Logo" class="w-14 h-14 rounded-full shadow transition-all duration-300 hover:scale-110">
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
  <section id="home" class="hero-bg h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Animated Background Image -->
    <div class="absolute inset-0 z-0">
        <div class="w-full h-full bg-cover bg-center animate-ken-burns" style="background-image: url('/assets/img/hero1.png');"></div>
    </div>
    
    <div class="hero-overlay z-10"></div>
    <div class="text-center text-white z-20 px-6">
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
          
          <!-- FAQ Chatbot Section -->
          <div class="contact-card" style="padding:0;">
            <div id="faq-chatbot">

              <!-- Header -->
              <div id="faq-chatbot-header">
                <div class="bot-avatar"><i class="bi bi-robot"></i></div>
                <div class="bot-info">
                  <h4>ServeDavao Assistant</h4>
                  <p>Ask me anything about the platform</p>
                </div>
                <span class="online-dot"></span>
              </div>

              <!-- Messages -->
              <div id="faq-messages">
                <!-- Welcome message injected by JS -->
              </div>

              <!-- Quick question buttons -->
              <div id="faq-quick-btns">
                <button class="faq-quick-btn" data-q="How do I become a volunteer?">🙋 Become a volunteer</button>
                <button class="faq-quick-btn" data-q="Can organizations post events?">🏢 Post events</button>
                <button class="faq-quick-btn" data-q="Is ServeDavao free?">💰 Is it free?</button>
                <button class="faq-quick-btn" data-q="How do I track my volunteer hours?">⏱️ Track hours</button>
                <button class="faq-quick-btn" data-q="How do I create an account?">📝 Create account</button>
                <button class="faq-quick-btn" data-q="What events are available?">📅 Events</button>
              </div>

              <!-- Input -->
              <div id="faq-input-area">
                <input id="faq-input" type="text" placeholder="Type your question..." autocomplete="off" />
                <button id="faq-send-btn"><i class="bi bi-send-fill"></i></button>
              </div>

            </div>
          </div>

          <script>
          (function () {

            /* =========================================================
               FAQ DATA  (label = shown on topic-pill buttons)
            ========================================================= */
            const faqs = [
              {
                label: '🙋 Become a volunteer',
                keywords: ['become a volunteer', 'how to volunteer', 'volunteer', 'join'],
                answer: '✅ To become a volunteer, simply create an account on ServeDavao, browse available events, and click <strong>Join</strong> on any event that interests you. Your participation will be tracked automatically!'
              },
              {
                label: '🏢 Post events',
                keywords: ['organization', 'org', 'post event', 'create event', 'organizer', 'manage event'],
                answer: '🏢 Yes! Organizations can register as an organizer to create, manage, and track volunteer events. Once approved, you can post events, verify attendance, and generate detailed reports.'
              },
              {
                label: '💰 Is it free?',
                keywords: ['free', 'cost', 'price', 'pay', 'charge', 'fee'],
                answer: '💰 ServeDavao is completely <strong>free</strong> for both volunteers and organizers! There are no hidden fees or subscriptions.'
              },
              {
                label: '⏱️ Track hours',
                keywords: ['track', 'hours', 'service hours', 'log hours', 'volunteer hours'],
                answer: '⏱️ Your volunteer hours are tracked automatically when an organizer verifies your attendance at an event. You can view your total hours and history in your profile dashboard.',
                followUp: {
                  label: '🌟 What are the benefits of always participating?',
                  key: 'benefits'
                }
              },
              {
                id: 'benefits',
                keywords: ['benefit', 'benefits', 'participating', 'always participate', 'always join', 'reward', 'advantage', 'why volunteer'],
                answer: '🌟 <strong>Benefits of Always Participating in ServeDavao:</strong><br><br>' +
                        '🏅 <strong>Recognition</strong> — Top volunteers are recognized and awarded each month.<br>' +
                        '📜 <strong>Certificates</strong> — Earn official certificates for completed hours — great for your resume!<br>' +
                        '📈 <strong>Skill Development</strong> — Gain hands-on experience and develop leadership skills.<br>' +
                        '🤝 <strong>Expand Your Network</strong> — Connect with like-minded community advocates across Davao.<br>' +
                        '📚 <strong>Learning Opportunities</strong> — Attend exclusive workshops and training sessions.<br>' +
                        '💚 <strong>Community Impact</strong> — Make a lasting difference in the lives of people in Davao City.<br><br>' +
                        'The more you participate, the more doors open for you!'
              },
              {
                label: '📝 Create account',
                keywords: ['create account', 'account', 'new user', 'sign up', 'register'],
                answer: '📝 Creating an account is easy! Click <strong>Get Started</strong> or <strong>Login</strong> at the top of the page, then choose <em>Register</em>. Fill in your details, verify your email, and you are ready to go!'
              },
              {
                label: '📅 Browse events',
                keywords: ['what events', 'available', 'browse', 'find event', 'upcoming'],
                answer: '📅 ServeDavao hosts a wide range of volunteer events across Davao City — from environmental drives and feeding programs to community outreach and disaster response. Browse the <strong>Events</strong> section after logging in!'
              },
              {
                label: '📅 Current Events',
                keywords: ['current events', 'ongoing events', 'ongoing', 'current event'],
                isCurrentEvents: true,
                answer: 'Fetching current events...'
              },
              {
                label: '📬 Contact us',
                keywords: ['contact', 'email', 'phone', 'reach', 'support'],
                answer: '📬 You can reach us at <strong>contact@servedavao.org</strong> or call us at <strong>+63 82 123 4567</strong>. We typically respond within 24 hours!'
              },
              /* Conversational — no follow-up needed */
              {
                conversational: true,
                keywords: ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening'],
                answer: '👋 Hello! I am the ServeDavao Assistant. What would you like to know today?'
              },
              {
                conversational: true,
                keywords: ['thank', 'thanks', 'salamat'],
                answer: '😊 You are very welcome! Feel free to ask anytime. Happy volunteering! 💚'
              }
            ];

            /* =========================================================
               DOM REFERENCES
            ========================================================= */
            const chatbot    = document.getElementById('faq-chatbot');
            const messagesEl = document.getElementById('faq-messages');
            const inputEl    = document.getElementById('faq-input');
            const sendBtn    = document.getElementById('faq-send-btn');
            const staticBtns = document.getElementById('faq-quick-btns');

            // Hide the static pill bar — pills live inside the chat now
            staticBtns.style.display = 'none';

            let isLocked = false;          // prevents sending while waiting for Yes/No
            let userHasInteracted = false;  // prevents auto-focus stealing keyboard on page load

            /* =========================================================
               HELPERS
            ========================================================= */
            function scrollBottom() {
              messagesEl.scrollTop = messagesEl.scrollHeight;
            }

            function escapeHtml(text) {
              if (!text) return '';
              const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
              };
              return text.replace(/[&<>"']/g, function(m) { return map[m]; });
            }

            /**
             * Add a chat message bubble.
             * Returns { wrap, bubble } so callers can append extra content.
             */
            function addMsg(html, role) {
              const wrap   = document.createElement('div');
              wrap.className = 'faq-msg ' + role;
              const avatar = document.createElement('div');
              avatar.className = 'msg-avatar';
              avatar.innerHTML = role === 'bot'
                ? '<i class="bi bi-robot"></i>'
                : '<i class="bi bi-person-fill"></i>';
              const bubble = document.createElement('div');
              bubble.className = 'faq-bubble';
              bubble.innerHTML = html;
              wrap.appendChild(avatar);
              wrap.appendChild(bubble);
              messagesEl.appendChild(wrap);
              scrollBottom();
              return { wrap, bubble };
            }

            /** Animated typing indicator. Returns the element so it can be removed. */
            function addTyping() {
              const { wrap } = addMsg(
                '<div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>',
                'bot'
              );
              wrap.id = 'faq-typing';
              return wrap;
            }

            /** Set locked state (disable input while waiting for Yes/No). */
            function setLocked(locked) {
              isLocked = locked;
              if (locked) {
                chatbot.classList.add('input-locked');
                inputEl.disabled = true;
                sendBtn.disabled = true;
              } else {
                chatbot.classList.remove('input-locked');
                inputEl.disabled = false;
                sendBtn.disabled = false;
                // Only focus the input after the user has intentionally interacted;
                // prevents the chatbot from stealing keyboard focus on page load.
                if (userHasInteracted) {
                  inputEl.focus({ preventScroll: true });
                }
              }
            }

            /** Find the best FAQ match for a query string. */
            function getAnswer(q) {
              const lower = q.toLowerCase();
              for (const faq of faqs) {
                if (faq.keywords.some(k => lower.includes(k))) return faq;
              }
              return null;
            }

            /* =========================================================
               GUIDED TOPIC MENU  (shown at start & after "Yes")
            ========================================================= */
            function showTopicMenu(introText) {
              const typingEl = addTyping();
              setTimeout(() => {
                typingEl.remove();
                const text = introText || '💬 Do you have any other questions? Choose a topic below or type it out:';
                
                const { bubble } = addMsg(text, 'bot');
                const pillsWrap = document.createElement('div');
                pillsWrap.className = 'faq-action-btns';
                pillsWrap.style.marginTop = '0.6rem';

                faqs.filter(f => f.label).forEach(faq => {
                  const btn = document.createElement('button');
                  btn.className = 'faq-quick-btn';
                  btn.textContent = faq.label;
                  btn.addEventListener('click', () => {
                    userHasInteracted = true;  // user has deliberately engaged
                    // Disable all pills in this group after selection
                    pillsWrap.querySelectorAll('button').forEach(b => {
                      b.disabled = true;
                      b.style.opacity = '0.45';
                    });
                    handleAnswer(faq.label, faq);
                  });
                  pillsWrap.appendChild(btn);
                });
                
                // Add a "No, I'm done" button at the end
                const doneBtn = document.createElement('button');
                doneBtn.className = 'faq-action-btn no';
                doneBtn.innerHTML = "👋 No, I'm done";
                doneBtn.addEventListener('click', () => {
                  userHasInteracted = true;
                  pillsWrap.querySelectorAll('button').forEach(b => {
                      b.disabled = true;
                      b.style.opacity = '0.45';
                  });
                  addMsg("No, that's all. Thank you!", 'user');
                  const tEl = addTyping();
                  setTimeout(() => {
                    tEl.remove();
                    addMsg(
                      '😊 Thank you for chatting with <strong>ServeDavao Assistant</strong>! ' +
                      'We hope we were helpful. If you need anything else, use the contact form below. Have a great day and happy volunteering! 💚',
                      'bot'
                    );
                    // Restart button
                    setTimeout(() => {
                      const { bubble: rb } = addMsg('', 'bot');
                      const restartBtn = document.createElement('button');
                      restartBtn.className = 'faq-action-btn restart';
                      restartBtn.innerHTML = '🔄 Start a new conversation';
                      restartBtn.addEventListener('click', restartChat);
                      rb.appendChild(restartBtn);
                      scrollBottom();
                    }, 300);
                  }, 950);
                });
                pillsWrap.appendChild(doneBtn);

                bubble.appendChild(pillsWrap);
                scrollBottom();
                setLocked(false);
              }, 750);
            }

            /* =========================================================
               ANSWER + FOLLOW-UP FLOW
            ========================================================= */
            function handleAnswer(userText, faqEntry) {
              // Show user message
              addMsg(userText, 'user');
              setLocked(true);

              const typingEl = addTyping();
              const delay = 850 + Math.random() * 350;

              setTimeout(() => {
                typingEl.remove();

                if (faqEntry && faqEntry.isCurrentEvents) {
                  fetch('/api/chatbot/current-events')
                    .then(res => res.json())
                    .then(data => {
                      if (data.success && data.events && data.events.length > 0) {
                        let html = '📅 <strong>Ongoing Events (Top ' + data.events.length + ' by participants):</strong><br><br>';
                        data.events.forEach((event, index) => {
                          html += `${index + 1}. <strong>${escapeHtml(event.title)}</strong><br>` +
                                  `👥 Registered: <strong>${event.current_volunteers}</strong>` +
                                  (event.required_volunteers ? ` / ${event.required_volunteers}` : '') + ' volunteers<br>' +
                                  `📍 Location: ${escapeHtml(event.location)}<br>` +
                                  `🔗 <a href="/events/${event.id}" class="text-emerald-600 font-semibold hover:underline" target="_blank">View Details</a><br><br>`;
                        });
                        addMsg(html, 'bot');
                      } else {
                        addMsg('📅 There are no ongoing volunteer events at the moment. Please check back later! 💚', 'bot');
                      }
                      setTimeout(showFollowup, 450);
                    })
                    .catch(err => {
                      console.error(err);
                      addMsg('❌ Sorry, I encountered an error while fetching the current events. Please try again later.', 'bot');
                      setTimeout(showFollowup, 450);
                    });
                  return;
                }

                const answer = faqEntry
                  ? faqEntry.answer
                  : "🤔 I'm not sure about that one. You can reach us at <strong>contact@servedavao.org</strong> or use the contact form below for specific questions.";
                const isConversational = faqEntry && faqEntry.conversational;

                if (isConversational) {
                  // Attach the topic options directly to the conversational answer
                  setTimeout(() => showTopicMenu(answer), 450);
                  return;
                }

                addMsg(answer, 'bot');

                // If this FAQ has a suggested follow-up question, show it before showing topics
                if (faqEntry && faqEntry.followUp) {
                  setTimeout(() => showSuggestedQuestion(faqEntry.followUp), 500);
                } else {
                  setTimeout(showFollowup, 450);
                }
              }, delay);
            }

            /**
             * Show a contextual follow-up suggestion button tied to a specific answer.
             * followUpDef = { label: string, key: string }
             */
            function showSuggestedQuestion(followUpDef) {
              setLocked(true);
              const { bubble } = addMsg(
                '💡 <strong>Related question</strong> — you might also want to know:',
                'bot'
              );

              const btns = document.createElement('div');
              btns.className = 'faq-action-btns';
              btns.style.marginTop = '0.55rem';

              /* Suggestion pill button */
              const suggestBtn = document.createElement('button');
              suggestBtn.className = 'faq-action-btn yes';
              suggestBtn.style.borderRadius = '0.6rem';   // slightly squarer for this one
              suggestBtn.style.padding = '0.45rem 1rem';
              suggestBtn.innerHTML = followUpDef.label;
              suggestBtn.addEventListener('click', () => {
                suggestBtn.disabled = true;
                skipBtn.disabled    = true;
                // Find the matching FAQ entry by id
                const entry = faqs.find(f => f.id === followUpDef.key) || null;
                handleAnswer(followUpDef.label, entry);
              });

              /* Skip button */
              const skipBtn = document.createElement('button');
              skipBtn.className = 'faq-action-btn no';
              skipBtn.innerHTML = '⏭️ Skip';
              skipBtn.addEventListener('click', () => {
                suggestBtn.disabled = true;
                skipBtn.disabled    = true;
                btns.style.transition = 'opacity 0.2s';
                btns.style.opacity = '0.3';
                setTimeout(showFollowup, 350);
              });

              btns.appendChild(suggestBtn);
              btns.appendChild(skipBtn);
              bubble.appendChild(btns);
              scrollBottom();
            }

            /** “Do you have any other questions?” Yes / No buttons */
            function showFollowup() {
              setLocked(true);
              const { bubble } = addMsg('💬 Was that helpful? Do you have any other questions?', 'bot');

              const btns = document.createElement('div');
              btns.className = 'faq-action-btns';
              btns.style.marginTop = '0.5rem';

              /* --- YES button --- */
              const yesBtn = document.createElement('button');
              yesBtn.className = 'faq-action-btn yes';
              yesBtn.innerHTML = '✅ Yes, I have more';
              yesBtn.addEventListener('click', () => {
                yesBtn.disabled = true;
                noBtn.disabled  = true;
                addMsg('Yes, I have more questions.', 'user');
                const typingEl = addTyping();
                setTimeout(() => {
                  typingEl.remove();
                  showTopicMenu('Great! Let me show you the topics again. 😊');
                }, 700);
              });

              /* --- NO button --- */
              const noBtn = document.createElement('button');
              noBtn.className = 'faq-action-btn no';
              noBtn.innerHTML = "👋 No, I'm done";
              noBtn.addEventListener('click', () => {
                yesBtn.disabled = true;
                noBtn.disabled  = true;
                addMsg("No, that's all. Thank you!", 'user');
                const typingEl = addTyping();
                setTimeout(() => {
                  typingEl.remove();
                  addMsg(
                    '😊 Thank you for chatting with <strong>ServeDavao Assistant</strong>! ' +
                    'We hope we were helpful. If you need anything else, use the contact form below. Have a great day and happy volunteering! 💚',
                    'bot'
                  );
                  // Restart button
                  setTimeout(() => {
                    const { bubble: rb } = addMsg('', 'bot');
                    const restartBtn = document.createElement('button');
                    restartBtn.className = 'faq-action-btn restart';
                    restartBtn.innerHTML = '🔄 Start a new conversation';
                    restartBtn.addEventListener('click', restartChat);
                    rb.appendChild(restartBtn);
                    scrollBottom();
                  }, 300);
                }, 950);
              });

              btns.appendChild(yesBtn);
              btns.appendChild(noBtn);
              bubble.appendChild(btns);
              scrollBottom();
            }

            /* =========================================================
               FREE-TEXT INPUT HANDLER
            ========================================================= */
            function sendFreeText(q) {
              if (!q.trim() || isLocked) return;
              userHasInteracted = true;  // user has deliberately engaged
              inputEl.value = '';
              const match = getAnswer(q);
              handleAnswer(q, match);
            }

            sendBtn.addEventListener('click',  () => sendFreeText(inputEl.value));
            inputEl.addEventListener('keydown', e => { if (e.key === 'Enter') sendFreeText(inputEl.value); });

            /* =========================================================
               RESTART
            ========================================================= */
            function restartChat() {
              messagesEl.innerHTML = '';
              isLocked = false;
              chatbot.classList.remove('input-locked');
              inputEl.disabled = false;
              sendBtn.disabled = false;
              inputEl.value = '';
              greet();
            }

            /* =========================================================
               GREETING SEQUENCE
            ========================================================= */
            function greet() {
              setLocked(true);
              setTimeout(() => {
                addMsg(
                  '👋 Hi there! I am the <strong>ServeDavao Assistant</strong>. ' +
                  "I'm here to help you with questions about volunteering, events, and our platform! Type your question below to get started.",
                  'bot'
                );
                setLocked(false);
              }, 400);
            }

            greet();

          })();
          </script>
        </div>
        
       <!-- Contact Form -->
<div class="bg-gray-50 rounded-2xl p-8 contact-card">
    <h3 class="text-2xl font-bold text-gray-800 mb-6">Send Us a Message</h3>
    <form id="contactFormSecure" action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Spam Protection Fields (Hidden from humans) -->
        <div style="display: none;">
            <input type="text" name="website" tabindex="-1" autocomplete="off">
            <input type="url" name="url" tabindex="-1" autocomplete="off">
        </div>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                <input type="text" id="firstName" name="firstName" required 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none contact-input"
                       placeholder="Your first name">
            </div>
            <div>
                <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                <input type="text" id="lastName" name="lastName" required 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none contact-input"
                       placeholder="Your last name">
            </div>
        </div>
        
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
            <input type="email" id="email" name="email" required 
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none contact-input"
                   placeholder="your.email@example.com">
        </div>
        
        <div>
            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
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
            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
            <textarea id="message" name="message" rows="5" required 
                      class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none contact-input resize-none"
                      placeholder="Tell us how we can help you..."></textarea>
        </div>
        
        <!-- reCAPTCHA -->
        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
        
        <button type="submit" 
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center justify-center gap-2">
            <span>Send Message</span>
            <i class="bi bi-send"></i>
        </button>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contactFormSecure');
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.getElementById('errorMessage');
        const successText = document.getElementById('successText');
        const errorText = document.getElementById('errorText');
        
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                successMessage.classList.add('hidden');
                errorMessage.classList.add('hidden');

                // reCAPTCHA check
                let recaptchaResponse = null;
                if (typeof grecaptcha !== 'undefined') {
                    recaptchaResponse = grecaptcha.getResponse();
                    if (!recaptchaResponse) {
                        errorText.textContent = 'Please complete the reCAPTCHA verification.';
                        errorMessage.classList.remove('hidden');
                        return;
                    }
                } else {
                    errorText.textContent = 'Verification service not loaded. Please refresh.';
                    errorMessage.classList.remove('hidden');
                    return;
                }
                
                const submitButton = contactForm.querySelector('button[type="submit"]');
                const originalContent = submitButton.innerHTML;
                submitButton.innerHTML = '<span>Sending...</span>';
                submitButton.disabled = true;
                
                const formData = new FormData(contactForm);
                formData.set('g-recaptcha-response', recaptchaResponse);
                
                fetch(contactForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(({ status, body }) => {
                    if (status >= 200 && status < 300 && body.success) {
                        successText.textContent = body.message || 'Message sent successfully!';
                        successMessage.classList.remove('hidden');
                        contactForm.reset();
                        if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
                    } else {
                        let msg = body.message || 'Error sending message.';
                        if (body.errors) {
                            const first = Object.values(body.errors)[0];
                            if (first) msg = first[0] || msg;
                        }
                        errorText.textContent = msg;
                        errorMessage.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    errorText.textContent = 'Network error. Please try again.';
                    errorMessage.classList.remove('hidden');
                })
                .finally(() => {
                    submitButton.innerHTML = originalContent;
                    submitButton.disabled = false;
                });
            });
        }
    });
    </script>
    
    <!-- SUCCESS Message (GREEN) -->
    <div id="successMessage" class="hidden mt-6 p-4 bg-emerald-100 border border-emerald-400 text-emerald-700 rounded-lg">
        <div class="flex items-center gap-2">
            <i class="bi bi-check-circle-fill text-emerald-600"></i>
            <p class="font-medium" id="successText">Thank you! Your message has been sent successfully.</p>
        </div>
    </div>
    
    <!-- ERROR Message (RED) -->
    <div id="errorMessage" class="hidden mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <div class="flex items-center gap-2">
            <i class="bi bi-exclamation-circle-fill text-red-600"></i>
            <p class="font-medium" id="errorText">There was an error sending your message. Please try again later.</p>
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
        <img src="/assets/img/logoDav.png" class="w-14 h-14 rounded-full mr-3 transform transition-transform duration-300 hover:scale-110">
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
  <!-- Scripts moved to resources/js/app.js -->
</body>
</html>