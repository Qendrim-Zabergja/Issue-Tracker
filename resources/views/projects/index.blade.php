@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="page-header">
    <h1 class="page-title">Projects</h1>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">+ New Project</a>
</div>

@if ($projects->isEmpty())
    <div class="empty-state">
        <h3>No projects yet</h3>
        <p>Create your first project to get started.</p>
    </div>
@else
    <div class="grid-3">
        @foreach ($projects as $project)
        <div class="card">
            <div class="project-card">
                <div>
                    <a href="{{ route('projects.show', $project) }}" class="project-card-title" style="display:block;">
                        {{ $project->name }}
                    </a>
                    <div class="project-card-meta">
                        <span>by {{ $project->user->name }}</span>
                        <span>{{ $project->issues_count }} issue{{ $project->issues_count !== 1 ? 's' : '' }}</span>
                        @if ($project->deadline)
                            <span>Due {{ $project->deadline->format('M d, Y') }}</span>
                        @endif
                    </div>
                </div>
                @if ($project->description)
                    <p class="project-card-desc">{{ Str::limit($project->description, 100) }}</p>
                @endif
                <div style="display:flex;gap:.5rem;margin-top:.25rem;">
                    <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline">View</a>
                    @if (auth()->id() === $project->user_id)
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <form action="{{ route('projects.delete', $project) }}" method="POST" onsubmit="return confirm('Delete this project?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="pagination">{{ $projects->links() }}</div>
@endif
@endsection
