@extends('layouts.app')

@section('title', 'New Project')

@section('content')
<div class="page-header">
    <h1 class="page-title">New Project</h1>
    <a href="{{ route('projects.index') }}" class="btn btn-secondary">← Back</a>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-body">
        @include('partials.errors')

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Project Name <span style="color:#ef4444">*</span></label>
                <input id="name" type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       value="{{ old('name') }}" required autofocus>
                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="start_date">Start Date</label>
                    <input id="start_date" type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                    @error('start_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="deadline">Deadline</label>
                    <input id="deadline" type="date" name="deadline" class="form-control" value="{{ old('deadline') }}">
                    @error('deadline') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Project</button>
                <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
