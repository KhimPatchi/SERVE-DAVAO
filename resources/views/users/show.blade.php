<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">View User</h1>
    <a href="{{ route('users.index') }}" class="btn btn-secondary mb-3">Back to Users</a>
    <ul class="list-group">
        <li class="list-group-item"><strong>ID:</strong> {{ $user->id }}</li>
        <li class="list-group-item"><strong>Name:</strong> {{ $user->name }}</li>
        <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
        <li class="list-group-item"><strong>Accepted:</strong> {{ $user->is_accepted ? 'Yes' : 'No' }}</li>
    </ul>
</div>
<script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
