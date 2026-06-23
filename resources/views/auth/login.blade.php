<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Issue Tracker</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f3f4f6;">

<div class="card" style="width:380px;">
    <div class="card-header" style="background:#1e1b4b;color:#fff;text-align:center;font-size:1rem;padding:1.1rem;">
        &#9654; Issue Tracker
    </div>
    <div class="card-body">
        <h2 style="font-size:1.15rem;margin-bottom:1.25rem;color:#111827;">Sign in to your account</h2>

        @include('partials.errors')

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email address</label>
                <input id="email" type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group" style="display:flex;align-items:center;gap:.5rem;">
                <input type="checkbox" id="remember" name="remember" style="width:auto;">
                <label for="remember" style="margin:0;font-weight:400;color:#4b5563;">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:.5rem;">
                Sign in
            </button>
        </form>

        <p class="text-muted text-sm mt-2" style="text-align:center;">
            Demo: alice@example.com / password
        </p>
    </div>
</div>

</body>
</html>
