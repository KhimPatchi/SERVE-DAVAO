<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ServeDavao Password Reset Request</title>

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
            padding: 20px;
        }

        .reset-request-card {
            background: #ffffff;
            color: #212529;
            padding: 3rem;
            border-radius: 1.5rem;
            box-shadow: 0 1rem 2rem rgba(0,0,0,0.3);
            width: 100%;
            max-width: 440px;
            animation: fadeInUp 0.8s ease-out;
        }

        .reset-request-card h1 {
            font-weight: 700;
            color: #1a9988;
            font-size: 1.75rem;
            text-align: center;
        }

        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
        }

        .btn-teal {
            background-color: #1a9988;
            border-color: #1a9988;
            color: white;
            transition: all 0.25s ease-in-out;
            border-radius: 0.5rem;
            font-weight: 600;
        }

        .btn-teal:hover {
            background-color: #147a6c;
            border-color: #147a6c;
            transform: translateY(-1px);
        }

        .text-muted a {
            color: #1a9988;
            text-decoration: none;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .preloader {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.75);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
            color: #1a9988;
        }

        .icon-floating {
            animation: float 3s ease-in-out infinite;
            text-align: center;
            margin-bottom: 20px;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
    </style>
</head>
<body>

    <!-- Preloader -->
    <div id="preloader" class="preloader">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="reset-request-card">
        <div class="icon-floating">
            <i class="bi bi-envelope-fill fs-1 text-teal"></i>
        </div>

        <h1>Password Reset</h1>
        <p class="text-muted mb-4 text-center">
            Forgot your password? No problem. Enter your email address and we will send you a link to reset it.
        </p>

        <form method="POST" action="{{ route('password.email') }}" id="requestForm">
            @csrf

            <!-- Email -->
            <div class="mb-3 text-start">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="form-control" placeholder="you@example.com">
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-teal w-100 py-2" id="requestButton">
                <i class="bi bi-envelope me-2"></i> Send Reset Link
            </button>

            <div class="text-center mt-3 text-muted">
                <a href="{{ route('login') }}" class="small">Back to Login</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const requestForm = document.getElementById('requestForm');
        const preloader = document.getElementById('preloader');
        const requestButton = document.getElementById('requestButton');

        requestForm.addEventListener('submit', function() {
            preloader.style.display = 'flex';
            requestButton.disabled = true;
        });

        window.addEventListener("pageshow", function () {
            preloader.style.display = "none";
            requestButton.disabled = false;
        });
    </script>

</body>
</html>
