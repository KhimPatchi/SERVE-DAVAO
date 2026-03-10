<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Edit User</h1>
    
    <a href="{{ route('users.index') }}" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Back to Users
    </a>

    <!-- Notifications -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control @error ('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error ('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control @error ('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error ('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-select @error ('role') is-invalid @enderror" required>
                        <option value="" disabled {{ !$user->role ? 'selected' : '' }}>Select Role</option>
                        <option value="volunteer" {{ (old('role', $user->role) == 'volunteer') ? 'selected' : '' }}>Volunteer</option>
                        <option value="organizer" {{ (old('role', $user->role) == 'organizer') ? 'selected' : '' }}>Organizer</option>
                        <option value="admin" {{ (old('role', $user->role) == 'admin') ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error ('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password <small class="text-muted">(leave blank to keep old)</small></label>
                    <input type="password" name="password" id="password" class="form-control @error ('password') is-invalid @enderror">
                    @error ('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_accepted" class="form-check-input @error ('is_accepted') is-invalid @enderror" id="accepted" {{ old('is_accepted', $user->is_accepted) ? 'checked' : '' }}>
                    <label class="form-check-label" for="accepted">Accepted?</label>
                    @error ('is_accepted')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-emerald text-white" style="background-color: #10b981;">Update User</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
