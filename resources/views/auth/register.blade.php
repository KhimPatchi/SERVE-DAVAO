<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ServeDavao | Register</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #000000ff;
    }

    .card {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      width: 100%;
      max-width: 420px;
    }

    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
    }

    .btn-teal {
      background-color: #1a9988;
      color: white;
      font-weight: 600;
      border-radius: 8px;
      transition: background-color 0.2s ease-in-out;
    }

    .btn-teal:hover {
      background-color: #157a6a;
    }

    .error-text {
      color: #e3342f;
      font-size: 0.875rem;
      margin-top: 3px;
    }

    /* ✅ FIX: Ensure input text is visible */
    input {
      color: #111827; /* Tailwind's text-gray-900 */
      background-color: #ffffff; /* solid white background */
    }

    input::placeholder {
      color: #6b7280; /* Tailwind's text-gray-500 */
      opacity: 1;
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen">

  <div class="card w-full p-6">
    <h2 class="text-2xl font-semibold text-center mb-6 text-gray-700">Create Your Account</h2>

    @if (session('success'))
      <div class="mb-4 text-green-600 text-center font-medium">{{ session('success') }}</div>
    @endif

    @if (session('error'))
      <div class="mb-4 text-red-600 text-center font-medium">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('register') }}" novalidate>
      @csrf

      <!-- Full Name -->
      <div class="mb-4">
        <label for="name" class="block font-medium text-gray-700">Full Name</label>
        <input 
          id="name" 
          type="text" 
          name="name" 
          value="{{ old('name') }}" 
          class="w-full px-3 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" 
          placeholder="Enter your full name">
        @error('name')
          <span class="error-text">⚠ {{ $message }}</span>
        @enderror
      </div>

      <!-- Email -->
      <div class="mb-4">
        <label for="email" class="block font-medium text-gray-700">Email Address</label>
        <input 
          id="email" 
          type="email" 
          name="email" 
          value="{{ old('email') }}" 
          class="w-full px-3 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" 
          placeholder="Enter your email">
        @error('email')
          <span class="error-text">⚠ {{ $message }}</span>
        @enderror
      </div>

      <!-- Password -->
      <div class="mb-4">
        <label for="password" class="block font-medium text-gray-700">Password</label>
        <div class="relative">
          <input 
            id="password" 
            type="password" 
            name="password" 
            class="w-full px-3 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" 
            placeholder="Enter your password">
          <span class="absolute right-3 top-3 text-gray-500 cursor-pointer" id="togglePassword">
            <i class="bi bi-eye-slash"></i>
          </span>
        </div>
        @error('password')
          <span class="error-text">⚠ {{ $message }}</span>
        @enderror
      </div>

      <!-- Confirm Password -->
      <div class="mb-6">
        <label for="password_confirmation" class="block font-medium text-gray-700">Confirm Password</label>
        <div class="relative">
          <input 
            id="password_confirmation" 
            type="password" 
            name="password_confirmation" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" 
            placeholder="Re-enter your password">
          <span class="absolute right-3 top-3 text-gray-500 cursor-pointer" id="toggleConfirmPassword">
            <i class="bi bi-eye-slash"></i>
          </span>
        </div>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn-teal w-full py-2 mt-2">
        <i class="bi bi-person-plus-fill me-2"></i> Register
      </button>
    </form>

    <!-- Redirect to Login -->
    <div class="text-center mt-4 text-gray-600">
      <p class="text-sm">Already have an account?
        <a href="{{ route('login') }}" class="font-semibold text-teal-700 hover:text-teal-900">Login here</a>.
      </p>
    </div>
  </div>

  <script>
    // ✅ Toggle password visibility
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
  </script>
</body>
</html>
