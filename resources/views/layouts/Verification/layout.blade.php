<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'ServeDavao Dashboard')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Fixed sidebar */
        #sidebar {
            width: 4rem; /* icons only */
            transition: width 0.3s ease;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 40;
        }

        /* Expand on hover */
        #sidebar:hover {
            width: 16rem; /* full sidebar */
        }

        /* Hide text when collapsed */
        #sidebar nav a span,
        #sidebar form button span {
            transition: opacity 0.3s ease, margin 0.3s ease;
            opacity: 0;
            margin-left: 0;
        }

        /* Show text on hover */
        #sidebar:hover nav a span,
        #sidebar:hover form button span {
            opacity: 1;
            margin-left: 1rem;
        }

        /* Adjust main content */
        .main-content {
            margin-left: 4rem;
            transition: margin-left 0.3s ease;
        }

        /* When sidebar expands, adjust main content */
        #sidebar:hover ~ .main-content {
            margin-left: 16rem;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-white border-r border-gray-200 flex flex-col shadow-md">
            <!-- Logo -->
            <div class="flex justify-center items-center py-6">
                <img src="{{ asset('assets/img/logoDav.png') }}" alt="ServeDavao Logo" class="w-20 h-20 object-contain">
            </div>

            <!-- Navigation -->
            <nav class="flex flex-col flex-grow space-y-1 px-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="bi bi-house-door-fill text-lg"></i>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
                <a href="{{ route('events.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="bi bi-calendar-event text-lg"></i>
                    <span class="text-sm font-medium">Events</span>
                </a>
                <a href="{{ route('volunteers') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="bi bi-people-fill text-lg"></i>
                    <span class="text-sm font-medium">Volunteer Opportunities</span>
                </a>
                <a href="{{ route('volunteers.my-events') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="bi bi-clock-fill text-lg"></i>
                    <span class="text-sm font-medium">My Events</span>
                </a>
                
                @auth
                    <!-- Organizer Verification Link -->
                    @if(!Auth::user()->isVerifiedOrganizer() && !Auth::user()->hasPendingVerification())
                    <a href="{{ route('organizer.verify') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition">
                        <i class="bi bi-patch-check text-lg"></i>
                        <span class="text-sm font-medium">Become an Organizer</span>
                    </a>
                    @endif

                    <!-- Verification Status Link -->
                    @if(Auth::user()->hasPendingVerification())
                    <a href="{{ route('organizer.verification.status') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-yellow-600 hover:bg-yellow-50 hover:text-yellow-700 transition">
                        <i class="bi bi-hourglass-split text-lg"></i>
                        <span class="text-sm font-medium">Verification Status</span>
                    </a>
                    @endif

                    <!-- Organized Events Link -->
                    @if(Auth::user()->isVerifiedOrganizer())
                    <a href="{{ route('volunteers.organized-events') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                        <i class="bi bi-person-badge text-lg"></i>
                        <span class="text-sm font-medium">Organized Events</span>
                    </a>
                    @endif
                @endauth
            </nav>

            <!-- Logout button at bottom -->
            <div class="mt-auto px-4 pb-4">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-red-500 hover:bg-red-50 hover:text-red-600 transition">
                        <i class="bi bi-box-arrow-right text-lg"></i>
                        <span class="text-sm font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-1">
            <!-- Page Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Organizer Verification')</h1>
                            <p class="mt-1 text-sm text-gray-600">@yield('page-description', 'Complete your organizer verification')</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in">
            <div class="flex items-center">
                <i class="bi bi-check-circle-fill mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in">
            <div class="flex items-center">
                <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <script>
        // Auto-hide success/error messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const messages = document.querySelectorAll('[class*="bg-"].fixed');
            messages.forEach(message => {
                setTimeout(() => {
                    message.style.opacity = '0';
                    message.style.transform = 'translateX(100%)';
                    setTimeout(() => message.remove(), 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>