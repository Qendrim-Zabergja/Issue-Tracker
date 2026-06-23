@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Project</h1>
    <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">← Back</a>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-body">
        @include('partials.errors')

        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf @method('PATCH')

            <div class="form-group">
                <label class="form-label" for="name">Project Name <span style="color:#ef4444">*</span></label>
                <input id="name" type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       value="{{ old('name', $project->name) }}" required>
                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description" class="form-control">{{ old('description', $project->description) }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="start_date">Start Date</label>
                    <input id="start_date" type="date" name="start_date" class="form-control"
                           value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}">
                    @error('start_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="deadline">Deadline</label>
                    <input id="deadline" type="date" name="deadline" class="form-control"
                           value="{{ old('deadline', $project->deadline?->format('Y-m-d')) }}">
                    @error('deadline') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
