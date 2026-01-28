<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('assets/img/logoDav.png') }}">

        <title>{{ $title ?? 'ServeDavao' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />

        <!-- Tailwind CSS -->
        <!-- Tailwind CSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Google reCAPTCHA -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            .text-emerald-600 { color: #059669; }
            .bg-emerald-600 { background-color: #059669; }
            .hover\:bg-emerald-700:hover { background-color: #047857; }
            .focus\:ring-emerald-500:focus { --tw-ring-color: #10b981; }
            .border-emerald-500 { border-color: #10b981; }
            
            /* Animations */
            .animate-fade-in {
                animation: fadeIn 0.6s ease-out forwards;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            {{ $slot }}
        </div>
    </body>
</html>
