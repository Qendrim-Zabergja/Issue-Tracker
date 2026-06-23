@extends('layouts.app')

@section('title', 'Edit Issue')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Issue</h1>
    <a href="{{ route('issues.show', $issue) }}" class="btn btn-secondary">← Back</a>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-body">
        @include('partials.errors')

        <form action="{{ route('issues.update', $issue) }}" method="POST">
            @csrf @method('PATCH')

            <div class="form-group">
                <label class="form-label" for="title">Title <span style="color:#ef4444">*</span></label>
                <input id="title" type="text" name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                       value="{{ old('title', $issue->title) }}" required>
                @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $issue->description) }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        @foreach (['open','in_progress','closed'] as $s)
                            <option value="{{ $s }}" {{ old('status', $issue->status) === $s ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', ucfirst($s)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="priority">Priority</label>
                    <select id="priority" name="priority" class="form-control">
                        @foreach (['low','medium','high'] as $p)
                            <option value="{{ $p }}" {{ old('priority', $issue->priority) === $p ? 'selected' : '' }}>
                                {{ ucfirst($p) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="due_date">Due Date</label>
                <input id="due_date" type="date" name="due_date" class="form-control"
                       value="{{ old('due_date', $issue->due_date?->format('Y-m-d')) }}">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('issues.show', $issue) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
