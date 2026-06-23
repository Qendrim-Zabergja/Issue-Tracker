@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $project->name }}</h1>
        <div class="text-sm text-muted mt-1">
            Owned by {{ $project->user->name }}
            @if ($project->start_date) &nbsp;·&nbsp; Started {{ $project->start_date->format('M d, Y') }} @endif
            @if ($project->deadline) &nbsp;·&nbsp; Due {{ $project->deadline->format('M d, Y') }} @endif
        </div>
    </div>
    <div style="display:flex;gap:.5rem;">
        <a href="{{ route('issues.create', ['project_id' => $project->uuid]) }}" class="btn btn-primary">+ New Issue</a>
        @if (auth()->id() === $project->user_id)
            <a href="{{ route('projects.edit', $project) }}" class="btn btn-secondary">Edit</a>
            <form action="{{ route('projects.delete', $project) }}" method="POST" onsubmit="return confirm('Delete this project and all its issues?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        @endif
    </div>
</div>

@if ($project->description)
    <p style="color:#4b5563;margin-bottom:1.25rem;">{{ $project->description }}</p>
@endif

<div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <span>Issues ({{ $project->issues->count() }})</span>
    </div>
    @if ($project->issues->isEmpty())
        <div class="empty-state" style="padding:2rem;">
            <p>No issues yet. <a href="{{ route('issues.create', ['project_id' => $project->uuid]) }}">Create the first one.</a></p>
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Tags</th>
                    <th>Comments</th>
                    <th>Due</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($project->issues as $issue)
                <tr>
                    <td><a href="{{ route('issues.show', $issue) }}">{{ $issue->title }}</a></td>
                    <td><span class="badge badge-{{ $issue->status }}">{{ str_replace('_', ' ', $issue->status) }}</span></td>
                    <td><span class="badge badge-{{ $issue->priority }}">{{ $issue->priority }}</span></td>
                    <td>
                        @foreach ($issue->tags as $tag)
                            <span class="tag-pill attached" style="background:{{ $tag->color }}22;color:{{ $tag->color }};border-color:{{ $tag->color }};">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </td>
                    <td class="text-muted">{{ $issue->comments_count }}</td>
                    <td class="text-muted text-sm">{{ $issue->due_date?->format('M d') ?? '—' }}</td>
                    <td>
                        <a href="{{ route('issues.show', $issue) }}" class="btn btn-xs btn-outline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
