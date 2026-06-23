@extends('layouts.app')

@section('title', 'New Issue')

@section('content')
<div class="page-header">
    <h1 class="page-title">New Issue</h1>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">← Back</a>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-body">
        @include('partials.errors')

        <form action="{{ route('issues.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="project_id">Project <span style="color:#ef4444">*</span></label>
                <select id="project_id" name="project_id" class="form-control {{ $errors->has('project_id') ? 'is-invalid' : '' }}" required>
                    <option value="">Select a project…</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->uuid }}"
                            {{ old('project_id', $selectedProject?->uuid) === $project->uuid ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="title">Title <span style="color:#ef4444">*</span></label>
                <input id="title" type="text" name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                       value="{{ old('title') }}" required autofocus>
                @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        @foreach (['open','in_progress','closed'] as $s)
                            <option value="{{ $s }}" {{ old('status', 'open') === $s ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', ucfirst($s)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="priority">Priority</label>
                    <select id="priority" name="priority" class="form-control">
                        @foreach (['low','medium','high'] as $p)
                            <option value="{{ $p }}" {{ old('priority', 'medium') === $p ? 'selected' : '' }}>
                                {{ ucfirst($p) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="due_date">Due Date</label>
                <input id="due_date" type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Issue</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
