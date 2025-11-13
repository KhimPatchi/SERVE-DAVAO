<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'ServeDavao Dashboard')</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/img/logoDav.png') }}">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    body { 
      font-family: 'Inter', sans-serif;
      transition: margin-left 0.3s ease;
    }

    /* Sidebar States */
    #sidebar {
      width: 4rem;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 40;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      overflow-x: hidden;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
    }

    #sidebar.expanded {
      width: 16rem;
    }

    #sidebar.collapsed {
      width: 4rem;
    }

    /* Enhanced Hover effect for desktop - REMOVED AUTO EXPANSION */
    @media (min-width: 768px) {
      /* Show toggle button on sidebar hover */
      #sidebar:hover ~ .sidebar-toggle,
      .sidebar-toggle:hover {
        opacity: 1;
        transform: translateX(0);
      }
      
      /* Enhanced logo animation */
      .sidebar-logo {
        transition: all 0.3s ease;
        filter: brightness(1);
      }
      
      .sidebar-logo:hover {
        filter: brightness(1.1);
      }
      
      /* Improved navigation item animations */
      .desktop-nav a {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
      }
      
      .desktop-nav a::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.05), transparent);
        transition: left 0.6s;
      }
      
      .desktop-nav a:hover::before {
        left: 100%;
      }
      
      /* Active state indicator - Black theme */
      .desktop-nav a.active {
        background: #f8fafc;
        color: #000000;
        border-right: 3px solid #000000;
        font-weight: 600;
      }
      
      .desktop-nav a.active i {
        color: #000000;
      }
      
      /* Enhanced icon animations */
      .desktop-nav a i {
        transition: all 0.3s ease;
        min-width: 1.5rem;
        text-align: center;
        color: #4b5563; /* Gray color for icons */
      }
      
      .desktop-nav a:hover i {
        transform: scale(1.1);
        color: #000000;
      }
      
      /* Text animation improvements */
      #sidebar.collapsed nav a span {
        opacity: 0;
        margin-left: 0;
        transform: translateX(-10px);
        transition: opacity 0.3s ease, margin 0.3s ease, transform 0.3s ease;
      }
      
      #sidebar.expanded nav a span {
        opacity: 1;
        margin-left: 1rem;
        transform: translateX(0);
        transition: opacity 0.3s ease 0.1s, margin 0.3s ease, transform 0.3s ease;
      }
    }

    /* Main content adjustment */
    .main-content {
      margin-left: 4rem;
      transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .main-content.expanded {
      margin-left: 16rem;
    }

    .main-content.collapsed {
      margin-left: 4rem;
    }

    /* Enhanced Sidebar toggle button - Hidden by default, shows on hover */
    .sidebar-toggle {
      position: fixed;
      top: 1rem;
      left: 1rem;
      z-index: 50;
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 0.75rem;
      padding: 0.6rem;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      opacity: 0;
      transform: translateX(-10px);
    }

    .sidebar-toggle:hover {
      background: #f8fafc;
      transform: scale(1.05) translateX(0);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .sidebar-toggle.active {
      background: #000000;
      color: white;
    }

    /* Mobile Styles */
    @media (max-width: 767px) {
      #sidebar {
        width: 100%;
        height: auto;
        position: fixed;
        bottom: 0;
        left: 0;
        top: auto;
        z-index: 40;
        transform: translateY(100%);
        transition: transform 0.3s ease;
      }

      #sidebar.mobile-open {
        transform: translateY(0);
      }

      .main-content {
        margin-left: 0 !important;
        margin-bottom: 0;
        padding-bottom: 5rem;
      }

      .sidebar-toggle {
        display: none;
      }

      .desktop-nav {
        display: none;
      }

      .mobile-nav {
        display: flex;
      }
      
      .mobile-bottom-nav,
      .mobile-menu-toggle {
        display: none;
      }
    }

    /* Desktop Styles */
    @media (min-width: 768px) {
      .mobile-nav,
      .mobile-bottom-nav,
      .mobile-menu-toggle {
        display: none;
      }

      .desktop-nav {
        display: flex;
      }
      
      /* Enhanced scrollbar for desktop */
      #sidebar::-webkit-scrollbar {
        width: 4px;
      }
      
      #sidebar::-webkit-scrollbar-track {
        background: #f1f5f9;
      }
      
      #sidebar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 2px;
      }
      
      #sidebar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
      }
    }

    /* Enhanced footer section */
    .sidebar-footer {
      border-top: 1px solid #f1f5f9;
      background: #fafafa;
    }
    
    /* Improved logout button - Black theme */
    .logout-btn {
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      color: #4b5563;
    }
    
    .logout-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.05), transparent);
      transition: left 0.6s;
    }
    
    .logout-btn:hover::before {
      left: 100%;
    }
    
    .logout-btn:hover {
      transform: translateX(2px);
      color: #000000;
    }

    /* Navigation link colors - Black theme */
    .desktop-nav a {
      color: #4b5563;
    }
    
    .desktop-nav a:hover {
      color: #000000;
      background: #f8fafc;
    }

    /* Profile icon specific color */
    .profile-icon {
      color: #8b5cf6; /* Purple color for profile */
    }
    
    .desktop-nav a:hover .profile-icon {
      color: #000000;
    }
    
    .desktop-nav a.active .profile-icon {
      color: #000000;
    }

    /* Avatar styles */
    .user-avatar {
      width: 2rem;
      height: 2rem;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #e5e7eb;
    }
    
    .avatar-initial {
      width: 2rem;
      height: 2rem;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 0.875rem;
      color: white;
    }

    /* Smooth backdrop filter for modern browsers */
    @supports (backdrop-filter: blur(10px)) {
      #sidebar {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
      }
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">
  <div class="flex flex-col min-h-screen">

    <!-- Enhanced Sidebar Toggle Button - Shows on hover -->
    <button id="sidebarToggle" class="sidebar-toggle hidden md:flex items-center justify-center">
      <i class="bi bi-chevron-double-right text-xl transition-transform duration-300"></i>
    </button>

    <!-- Sidebar -->
    <div id="sidebar" class="bg-white border-r border-gray-200 flex flex-col shadow-xl collapsed">
      <!-- Enhanced Logo Section -->
      <div class="hidden md:flex justify-center items-center py-6 px-2">
        <div class="flex items-center gap-3 transition-all duration-300">
          <img src="{{ asset('assets/img/logoDav.png') }}" alt="ServeDavao Logo" 
               class="sidebar-logo w-10 h-10 md:w-16 md:h-16 object-contain">
        </div>
      </div>

      <!-- Enhanced Desktop Navigation - Black Color Scheme -->
      <nav class="desktop-nav flex-col flex-grow space-y-2 px-3 hidden md:flex py-4">
        @auth
          @if(Auth::user()->isAdmin())
            <!-- ADMIN ONLY LINKS - Black Color Scheme -->
            <div class="px-3 py-2">
              <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider collapsed-text">Main Admin</span>
            </div>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300">
              <i class="bi bi-speedometer2 text-lg"></i>
              <span class="text-sm font-medium collapsed-text">Admin Dashboard</span>
            </a>
            <a href="{{ route('admin.organizer-verifications') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300">
              <i class="bi bi-person-check text-lg"></i>
              <span class="text-sm font-medium collapsed-text">Organizer Verifications</span>
            </a>
            <a href="{{ route('admin.events') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300">
              <i class="bi bi-calendar-check text-lg"></i>
              <span class="text-sm font-medium collapsed-text">Manage Events</span>
            </a>
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300">
              <i class="bi bi-people text-lg"></i>
              <span class="text-sm font-medium collapsed-text">Manage Users</span>
            </a>

            <!-- AUDIT SECTION - Simple and Functional -->
            <div class="px-3 py-2 mt-4">
              <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider collapsed-text">Audit</span>
            </div>

            <a href="{{ route('admin.admin.audit.logs') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300">
              <i class="bi bi-journal-text text-lg"></i>
              <span class="text-sm font-medium collapsed-text">Audit Logs</span>
            </a>

          @else
            <!-- REGULAR USER LINKS - Black Color Scheme -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300">
              <i class="bi bi-house-door-fill text-lg"></i>
              <span class="text-sm font-medium collapsed-text">Dashboard</span>
            </a>
            <a href="{{ route('events.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300">
              <i class="bi bi-calendar-event text-lg"></i>
              <span class="text-sm font-medium collapsed-text">Events</span>
            </a>
            <a href="{{ route('volunteers') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300">
              <i class="bi bi-people-fill text-lg"></i>
              <span class="text-sm font-medium collapsed-text">Volunteer Opportunities</span>
            </a>
            <a href="{{ route('volunteers.my-events') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300">
              <i class="bi bi-clock-fill text-lg"></i>
              <span class="text-sm font-medium collapsed-text">My Events</span>
            </a>
            
            <!-- Organizer Links - Black Color Scheme -->
            @if(!Auth::user()->isVerifiedOrganizer() && !Auth::user()->hasPendingVerification())
            <a href="{{ route('organizer.verification.create') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300">
                <i class="bi bi-patch-check text-lg"></i>
                <span class="text-sm font-medium collapsed-text">Get Verified</span>
            </a>
            @endif

            @if(Auth::user()->hasPendingVerification())
            <a href="{{ route('organizer.verification.status') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300">
                <i class="bi bi-hourglass-split text-lg"></i>
                <span class="text-sm font-medium collapsed-text">Verification Pending</span>
            </a>
            @endif

            @if(Auth::user()->isVerifiedOrganizer())
            <a href="{{ route('volunteers.organized-events') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300">
                <i class="bi bi-person-badge text-lg"></i>
                <span class="text-sm font-medium collapsed-text">Organized Events</span>
            </a>
            @endif

            <!-- Profile Link - Black Color Scheme with specific profile color -->
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300">
                <i class="bi bi-person text-lg profile-icon"></i>
                <span class="text-sm font-medium collapsed-text">Profile</span>
            </a>
          @endif
        @endauth
      </nav>

      <!-- Enhanced Footer Section -->
      <div class="sidebar-footer mt-auto px-3 py-4 hidden md:block">
        <div class="space-y-3">
          <!-- User Info (visible when expanded) - FIXED AVATAR DISPLAY -->
          <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-gray-50 opacity-0 transition-opacity duration-300 collapsed-text">
            <!-- FIXED AVATAR DISPLAY - Using same logic as events show page -->
            @php
                $user = Auth::user();
                // COMPREHENSIVE AVATAR FIX FOR SIDEBAR
                $avatarUrl = null;
                if ($user && $user->avatar) {
                    if (str_starts_with($user->avatar, 'http')) {
                        $avatarUrl = $user->avatar;
                    } elseif (str_starts_with($user->avatar, 'storage/')) {
                        $avatarUrl = asset($user->avatar);
                    } else {
                        $avatarUrl = asset('storage/' . $user->avatar);
                    }
                }
                // Also check for google_avatar
                if (!$avatarUrl && $user && $user->google_avatar) {
                    $avatarUrl = $user->google_avatar;
                }
                $hasValidAvatar = $avatarUrl && filter_var($avatarUrl, FILTER_VALIDATE_URL);
                $userInitial = $user ? strtoupper(substr($user->name, 0, 1)) : 'U';
                
                // Determine avatar background based on registration method
                $avatarBackground = 'bg-gradient-to-r from-purple-500 to-indigo-600';
                if ($user && $user->provider) {
                    switch ($user->provider) {
                        case 'google':
                            $avatarBackground = 'bg-gradient-to-r from-red-500 to-yellow-500';
                            break;
                        case 'facebook':
                            $avatarBackground = 'bg-gradient-to-r from-blue-600 to-blue-800';
                            break;
                        case 'github':
                            $avatarBackground = 'bg-gradient-to-r from-gray-700 to-gray-900';
                            break;
                        default:
                            $avatarBackground = 'bg-gradient-to-r from-purple-500 to-indigo-600';
                    }
                }
            @endphp

            @if($hasValidAvatar)
                <!-- Show actual avatar image with error handling -->
                <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" 
                     class="w-8 h-8 rounded-full border-2 border-gray-200 object-cover"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-8 h-8 rounded-full {{ $avatarBackground }} flex items-center justify-center border-2 border-gray-200" style="display: none;">
                    <span class="text-white font-bold text-xs">
                        {{ $userInitial }}
                    </span>
                </div>
            @else
                <!-- Show colored initial based on registration method -->
                <div class="w-8 h-8 rounded-full {{ $avatarBackground }} flex items-center justify-center border-2 border-gray-200">
                    <span class="text-white font-bold text-xs">
                        {{ $userInitial }}
                    </span>
                </div>
            @endif

            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name ?? 'User' }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $user->email ?? '' }}</p>
                @if($user && $user->provider)
                    <p class="text-xs text-gray-400 capitalize">
                        {{ $user->provider }} account
                    </p>
                @else
                    <!-- Regular account indicator -->
                @endif
            </div>
          </div>
          
          <!-- Enhanced Logout button - Black Theme -->
          <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn flex items-center gap-3 w-full px-3 py-3 rounded-xl transition-all duration-300">
              <i class="bi bi-box-arrow-right text-lg"></i>
              <span class="text-sm font-medium collapsed-text">Logout</span>
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content flex-1 p-6 md:p-8 lg:p-10 collapsed">
      @yield('content')
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.querySelector('.main-content');
      const sidebarToggle = document.getElementById('sidebarToggle');
      const collapsedTexts = document.querySelectorAll('.collapsed-text');
      const toggleIcon = sidebarToggle.querySelector('i');
      
      // Initialize sidebar state from localStorage
      const savedState = localStorage.getItem('sidebarState');
      if (savedState === 'expanded') {
        expandSidebar();
      } else {
        collapseSidebar();
      }
      
      // Enhanced Desktop sidebar toggle - OPTIONAL for user
      if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
          if (sidebar.classList.contains('collapsed')) {
            expandSidebar();
          } else {
            collapseSidebar();
          }
        });
      }

      // Enhanced hover behavior for desktop - toggle appears on hover
      if (window.innerWidth >= 768) {
        sidebar.addEventListener('mouseenter', function() {
          // Show toggle button when hovering sidebar
          sidebarToggle.style.opacity = '1';
          sidebarToggle.style.transform = 'translateX(0)';
        });
        
        sidebar.addEventListener('mouseleave', function() {
          // Hide toggle button when not hovering (unless toggle is being hovered)
          if (!sidebarToggle.matches(':hover')) {
            sidebarToggle.style.opacity = '0';
            sidebarToggle.style.transform = 'translateX(-10px)';
          }
        });
        
        // Keep toggle visible when hovering over it
        sidebarToggle.addEventListener('mouseenter', function() {
          this.style.opacity = '1';
          this.style.transform = 'translateX(0)';
        });
        
        sidebarToggle.addEventListener('mouseleave', function() {
          this.style.opacity = '0';
          this.style.transform = 'translateX(-10px)';
        });
      }

      // Set active state for navigation items with persistent styling
      const navItems = document.querySelectorAll('.desktop-nav a');
      
      // Function to set active state
      function setActiveNavItem(clickedItem) {
        // Remove active class from all items
        navItems.forEach(i => i.classList.remove('active'));
        
        // Add active class to clicked item
        clickedItem.classList.add('active');
        
        // Store active state in localStorage
        localStorage.setItem('activeNavItem', clickedItem.getAttribute('href'));
      }
      
      // Add click event listeners to all nav items
      navItems.forEach(item => {
        item.addEventListener('click', function(e) {
          setActiveNavItem(this);
        });
      });
      
      // Restore active state on page load based on current URL
      function setActiveNavBasedOnCurrentPage() {
        const currentPath = window.location.pathname;
        let activeFound = false;
        
        navItems.forEach(item => {
          const href = item.getAttribute('href');
          if (href && currentPath.startsWith(href)) {
            setActiveNavItem(item);
            activeFound = true;
          }
        });
        
        // If no exact match found, try partial matches
        if (!activeFound) {
          navItems.forEach(item => {
            const href = item.getAttribute('href');
            if (href && currentPath.includes(href.replace(/^\//, ''))) {
              setActiveNavItem(item);
              activeFound = true;
            }
          });
        }
        
        // Fallback to stored active state
        if (!activeFound) {
          const activeNavItem = localStorage.getItem('activeNavItem');
          if (activeNavItem) {
            const itemToActivate = document.querySelector(`.desktop-nav a[href="${activeNavItem}"]`);
            if (itemToActivate) {
              itemToActivate.classList.add('active');
            }
          } else if (navItems.length > 0) {
            // Set first item as active by default if no active state is stored
            setActiveNavItem(navItems[0]);
          }
        }
      }
      
      // Set active state on page load
      setActiveNavBasedOnCurrentPage();

      function expandSidebar() {
        sidebar.classList.remove('collapsed');
        sidebar.classList.add('expanded');
        mainContent.classList.remove('collapsed');
        mainContent.classList.add('expanded');
        sidebarToggle.classList.add('active');
        
        // Show all text elements
        collapsedTexts.forEach(el => {
          el.style.opacity = '1';
        });
        
        // Rotate toggle icon
        toggleIcon.style.transform = 'rotate(180deg)';
        
        localStorage.setItem('sidebarState', 'expanded');
      }
      
      function collapseSidebar() {
        sidebar.classList.remove('expanded');
        sidebar.classList.add('collapsed');
        mainContent.classList.remove('expanded');
        mainContent.classList.add('collapsed');
        sidebarToggle.classList.remove('active');
        
        // Hide text elements
        collapsedTexts.forEach(el => {
          el.style.opacity = '0';
        });
        
        // Reset toggle icon
        toggleIcon.style.transform = 'rotate(0deg)';
        
        localStorage.setItem('sidebarState', 'collapsed');
      }
    });
  </script>
</body>
</html>