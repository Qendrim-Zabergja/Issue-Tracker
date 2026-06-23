@if (session('success'))
    <div class="alert alert-success">&#10003; {{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger">&#9888; {{ session('error') }}</div>
@endif
