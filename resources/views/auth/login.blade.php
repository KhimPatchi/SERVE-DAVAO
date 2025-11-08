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

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .login-card {
            background: #fff;
            color: #212529;
            padding: 3rem 2rem;
            border-radius: 1.5rem;
            box-shadow: 0 1rem 2rem rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
            text-align: center;
            position: relative;
        }

        .login-card h1 { color: #1a9988; font-weight: 700; }
        .form-control { border-radius: 0.5rem; padding: 0.75rem 1rem; }

        .btn-teal {
            background-color: #1a9988;
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 0.5rem;
            width: 100%;
        }
        .btn-teal:hover { background-color: #147a6c; }

        .btn-google {
            background-color: #fff;
            color: #212529;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
        }
        .btn-google:hover { background-color: #f5f5f5; }

        /* Enhanced back button styles */
        .btn-back {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            background-color: #f8f9fa;
            border: 2px solid #1a9988;
            color: #1a9988;
            font-size: 1.5rem;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn-back:hover {
            background-color: #1a9988;
            color: white;
            transform: translateX(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .btn-back-text {
            position: absolute;
            top: 1.5rem;
            left: 4.5rem;
            background-color: #1a9988;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.3s ease;
            white-space: nowrap;
            pointer-events: none;
        }

        .btn-back:hover + .btn-back-text {
            opacity: 1;
            transform: translateX(0);
        }

        .icon-floating { animation: float 3s ease-in-out infinite; }
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    </style>
</head>
<body>

<div class="login-card">

    <!-- Prevent logged-in users from accessing login -->
    @if (Auth::check())
        <script>window.location.href = "{{ route('dashboard') }}";</script>
    @endif

    <!-- Enhanced Back Button -->
    <button class="btn-back" onclick="window.location.href='{{ route('landing') }}'" title="Go back">
        <i class="bi bi-arrow-left"></i>
    </button>
    <div class="btn-back-text">Go Back</div>

    <!-- Floating Icon -->
    <div class="mb-4 icon-floating">
        <i class="bi bi-people-fill fs-1 text-teal"></i>
    </div>

    <h1>ServeDavao</h1>
    <p class="text-muted mb-4">Empowering volunteers to serve the Davao community.</p>

    <!-- Show Error Message -->
    @if ($errors->any())
        <div class="alert alert-danger text-start small">
            <strong>Invalid credentials:</strong> Please check your email and password.
        </div>
    @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Error:</strong> {{ session('error') }}
        </div>
    @endif

    <!-- Traditional Login -->
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3 text-start">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" name="email" id="email" required class="form-control" placeholder="you@example.com" value="{{ old('email') }}">
        </div>

        <div class="mb-3 text-start">
            <label for="password" class="form-label fw-semibold">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" required class="form-control" placeholder="••••••••">
                <span class="input-group-text" id="togglePassword"><i class="bi bi-eye-slash"></i></span>
            </div>
        </div>

        <div class="form-check text-start mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label small" for="remember">Remember me</label>
        </div>

        <button type="submit" class="btn btn-teal mb-3"><i class="bi bi-box-arrow-in-right me-2"></i> Log In</button>
    </form>

    <!-- Google Login -->
    <a href="{{ route('google.login') }}" class="btn btn-google mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 533.5 544.3" style="margin-right: 8px;">
            <path fill="#4285F4" d="M533.5 278.4c0-18.1-1.5-35.3-4.3-52H272v98.8h146.9c-6.4 34.8-25 64.4-53.6 84.2v69h86.6c50.8-46.8 80-115.8 80-199z"/>
            <path fill="#34A853" d="M272 544.3c72.6 0 133.6-23.8 178.2-64.9l-86.6-69c-24.1 16.2-55 25.6-91.6 25.6-70.4 0-130-47.5-151.4-111.2H32v69.8C76.5 484.3 167.2 544.3 272 544.3z"/>
            <path fill="#FBBC05" d="M120.6 324.6c-5.4-16.1-8.5-33.1-8.5-50.6s3.1-34.5 8.5-50.6v-69.8H32C11.3 206 0 241.5 0 278.4s11.3 72.5 32 100.2l88.6-69z"/>
            <path fill="#EA4335" d="M272 107.9c39.5 0 74.9 13.6 102.8 40.4l77.1-77.1C405.7 24.7 344.7 0 272 0 167.2 0 76.5 60 32 146.6l88.6 69C142 155.4 201.6 107.9 272 107.9z"/>
        </svg>
        Sign in with Google
    </a>

    <p class="text-muted small mt-2">
        Don't have an account? <a href="{{ route('register') }}">Register here</a>
    </p>

</div>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Password toggle
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", () => {
            const type = passwordInput.type === "password" ? "text" : "password";
            passwordInput.type = type;
            togglePassword.querySelector('i').classList.toggle("bi-eye");
            togglePassword.querySelector('i').classList.toggle("bi-eye-slash");
        });
    }

    // Nuclear option for back button prevention
    history.pushState(null, null, document.URL);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, document.URL);
    });

    // Prevent any navigation away from login page
    window.history.pushState(null, null, window.location.href);
    window.onpopstate = function() {
        window.history.go(1);
    };
</script>   
</body>
</html>