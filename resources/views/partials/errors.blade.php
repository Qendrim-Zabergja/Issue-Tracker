@if ($errors->any())
    <div class="alert alert-danger" style="flex-direction:column;align-items:flex-start;gap:.25rem;">
        @foreach ($errors->all() as $error)
            <div>&#9888; {{ $error }}</div>
        @endforeach
    </div>
@endif
