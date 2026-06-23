@extends('layouts.app')

@section('title', 'Tags')

@section('content')
<div class="page-header">
    <h1 class="page-title">Tags</h1>
</div>

@include('partials.flash')

<div class="tag-layout">
    {{-- TAG LIST --}}
    <div>
        <div class="card">
            @if ($tags->isEmpty())
                <div class="empty-state" style="padding:2.5rem;">
                    <h3>No tags yet</h3>
                    <p>Create your first tag using the form.</p>
                </div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tag</th>
                            <th>Color</th>
                            <th>Issues</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tags as $tag)
                        <tr>
                            <td>
                                <span class="tag-pill attached"
                                      style="background:{{ $tag->color }}22;color:{{ $tag->color }};border-color:{{ $tag->color }};">
                                    {{ $tag->name }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:.5rem;">
                                    <span style="display:inline-block;width:1.25rem;height:1.25rem;border-radius:3px;background:{{ $tag->color ?? '#94a3b8' }};border:1px solid #e2e8f0;"></span>
                                    <code style="font-size:.8rem;color:#6b7280;">{{ $tag->color ?? '—' }}</code>
                                </div>
                            </td>
                            <td class="text-muted">{{ $tag->issues_count }}</td>
                            <td>
                                <form action="{{ route('tags.delete', $tag) }}" method="POST"
                                      onsubmit="return confirm('Delete tag \'{{ addslashes($tag->name) }}\'? It will be removed from all issues.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="padding:.75rem 1rem;">{{ $tags->links() }}</div>
            @endif
        </div>
    </div>

    {{-- CREATE FORM --}}
    <div>
        <div class="card">
            <div class="card-header">New Tag</div>
            <div class="card-body">
                @include('partials.errors')

                <form action="{{ route('tags.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="name">Name <span style="color:#ef4444">*</span></label>
                        <input id="name" type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name') }}" required autofocus placeholder="e.g. bug, feature…">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="color">Color</label>
                        <div style="display:flex;align-items:center;gap:.5rem;">
                            <input id="color" type="color" name="color"
                                   class="form-control"
                                   style="width:3rem;height:2.25rem;padding:.15rem .25rem;cursor:pointer;"
                                   value="{{ old('color', '#6366f1') }}">
                            <input id="color-hex" type="text" class="form-control"
                                   style="font-family:monospace;font-size:.85rem;"
                                   placeholder="#6366f1"
                                   value="{{ old('color', '#6366f1') }}"
                                   maxlength="7">
                        </div>
                        @error('color') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Create Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const picker = document.getElementById('color');
    const hex    = document.getElementById('color-hex');

    picker.addEventListener('input', function () {
        hex.value = this.value;
    });

    hex.addEventListener('input', function () {
        const val = this.value.trim();
        if (/^#[0-9a-fA-F]{6}$/.test(val)) {
            picker.value = val;
        }
    });
})();
</script>
@endpush
