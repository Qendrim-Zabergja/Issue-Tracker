@extends('layouts.app')

@section('title', 'Issues')

@section('content')
<div class="page-header">
    <h1 class="page-title">Issues</h1>
    <a href="{{ route('issues.create') }}" class="btn btn-primary">+ New Issue</a>
</div>

<form method="GET" action="{{ route('issues.index') }}" id="filters-form">
    <div class="filters-bar">
        <input type="search" name="filter[search]" class="form-control" placeholder="Search issues…"
               value="{{ request('filter.search') }}" id="search-input" autocomplete="off">

        <select name="filter[status]" class="form-control" onchange="this.form.submit()">
            <option value="">All statuses</option>
            @foreach (['open','in_progress','closed'] as $s)
                <option value="{{ $s }}" {{ request('filter.status') === $s ? 'selected' : '' }}>
                    {{ str_replace('_', ' ', ucfirst($s)) }}
                </option>
            @endforeach
        </select>

        <select name="filter[priority]" class="form-control" onchange="this.form.submit()">
            <option value="">All priorities</option>
            @foreach (['low','medium','high'] as $p)
                <option value="{{ $p }}" {{ request('filter.priority') === $p ? 'selected' : '' }}>
                    {{ ucfirst($p) }}
                </option>
            @endforeach
        </select>

        <select name="filter[tag]" class="form-control" onchange="this.form.submit()">
            <option value="">All tags</option>
            @foreach ($tags as $tag)
                <option value="{{ $tag->uuid }}" {{ request('filter.tag') === $tag->uuid ? 'selected' : '' }}>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>

        @if (request()->hasAny(['filter']))
            <a href="{{ route('issues.index') }}" class="btn btn-secondary btn-sm">Clear</a>
        @endif
    </div>
</form>

<div class="card">
    @if ($issues->isEmpty())
        <div class="empty-state" style="padding:2.5rem;">
            <h3>No issues found</h3>
            <p>Try adjusting your filters or <a href="{{ route('issues.create') }}">create a new issue</a>.</p>
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Project</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Tags</th>
                    <th>Due</th>
                </tr>
            </thead>
            <tbody id="issues-tbody">
                @foreach ($issues as $issue)
                <tr>
                    <td><a href="{{ route('issues.show', $issue) }}">{{ $issue->title }}</a></td>
                    <td>
                        @if ($issue->project)
                            <a href="{{ route('projects.show', $issue->project) }}" class="text-muted text-sm">{{ $issue->project->name }}</a>
                        @else
                            <span class="text-muted text-sm">—</span>
                        @endif
                    </td>
                    <td><span class="badge badge-{{ $issue->status }}">{{ str_replace('_', ' ', $issue->status) }}</span></td>
                    <td><span class="badge badge-{{ $issue->priority }}">{{ $issue->priority }}</span></td>
                    <td>
                        @foreach ($issue->tags as $tag)
                            <span class="tag-pill attached" style="background:{{ $tag->color }}22;color:{{ $tag->color }};border-color:{{ $tag->color }};">{{ $tag->name }}</span>
                        @endforeach
                    </td>
                    <td class="text-muted text-sm">{{ $issue->due_date?->format('M d, Y') ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:.75rem 1rem;">{{ $issues->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    const input = document.getElementById('search-input');
    const form  = document.getElementById('filters-form');
    let timer;
    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => form.submit(), 400);
    });
})();
</script>
@endpush
