@extends('layouts.app')

@section('title', $issue->title)

@section('content')
<div class="page-header">
    <div>
        <div class="text-sm text-muted mb-1">
            <a href="{{ route('projects.show', $issue->project) }}">{{ $issue->project->name }}</a> &rsaquo; Issue
        </div>
        <h1 class="page-title">{{ $issue->title }}</h1>
    </div>
    <div style="display:flex;gap:.5rem;">
        <a href="{{ route('issues.edit', $issue) }}" class="btn btn-secondary">Edit</a>
        <form action="{{ route('issues.delete', $issue) }}" method="POST" onsubmit="return confirm('Delete this issue?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger">Delete</button>
        </form>
    </div>
</div>

<div class="issue-layout">
    {{-- MAIN COLUMN --}}
    <div>
        <div class="card mb-2">
            <div class="card-body">
                <div class="issue-meta-row">
                    <span class="badge badge-{{ $issue->status }}">{{ str_replace('_', ' ', $issue->status) }}</span>
                    <span class="badge badge-{{ $issue->priority }}">{{ $issue->priority }} priority</span>
                    @if ($issue->due_date)
                        <span class="text-sm text-muted">Due {{ $issue->due_date->format('M d, Y') }}</span>
                    @endif
                </div>

                @if ($issue->description)
                    <div class="issue-description">{{ $issue->description }}</div>
                @else
                    <p class="text-muted text-sm" style="margin-top:.5rem;">No description provided.</p>
                @endif
            </div>
        </div>

        {{-- COMMENTS --}}
        <div class="card">
            <div class="card-header">Comments</div>
            <div class="card-body">
                <div id="comment-list" class="comment-list">
                    <div class="spinner" id="comments-loading"></div>
                </div>

                <div id="load-more-wrap" class="hidden" style="text-align:center;margin-top:.75rem;">
                    <button id="load-more-btn" class="btn btn-secondary btn-sm">Load more</button>
                </div>

                <div class="comment-form">
                    <h3 style="font-size:.9rem;font-weight:600;margin-bottom:.85rem;">Add a comment</h3>

                    <div id="comment-errors" class="hidden"></div>

                    <div class="form-group">
                        <label class="form-label" for="comment-author">Your name <span style="color:#ef4444">*</span></label>
                        <input id="comment-author" type="text" class="form-control" placeholder="Name…">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="comment-body">Comment <span style="color:#ef4444">*</span></label>
                        <textarea id="comment-body" class="form-control" rows="3" placeholder="Write a comment…"></textarea>
                    </div>
                    <button id="comment-submit" class="btn btn-primary">Post Comment</button>
                </div>
            </div>
        </div>
    </div>

    {{-- SIDEBAR --}}
    <div>
        {{-- TAGS --}}
        <div class="card mb-2">
            <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
                Tags
                <button class="btn btn-xs btn-outline" onclick="document.getElementById('tag-modal').classList.remove('hidden')">
                    Manage
                </button>
            </div>
            <div class="card-body" id="issue-tags-display">
                @forelse ($issue->tags as $tag)
                    <span class="tag-pill attached" style="background:{{ $tag->color }}22;color:{{ $tag->color }};border-color:{{ $tag->color }};">
                        {{ $tag->name }}
                    </span>
                @empty
                    <span class="text-muted text-sm">No tags</span>
                @endforelse
            </div>
        </div>

        {{-- ASSIGNEES --}}
        <div class="card mb-2">
            <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
                Assignees
                <button class="btn btn-xs btn-outline" onclick="document.getElementById('assignee-modal').classList.remove('hidden')">
                    Manage
                </button>
            </div>
            <div class="card-body" id="issue-assignees-display">
                @forelse ($issue->assignees as $assignee)
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.35rem;">
                        <span class="avatar">{{ strtoupper(substr($assignee->name, 0, 1)) }}</span>
                        <span class="text-sm">{{ $assignee->name }}</span>
                    </div>
                @empty
                    <span class="text-muted text-sm">No assignees</span>
                @endforelse
            </div>
        </div>

        {{-- META --}}
        <div class="card">
            <div class="card-body">
                <div class="sidebar-section">
                    <div class="sidebar-title">Project</div>
                    <a href="{{ route('projects.show', $issue->project) }}" class="text-sm">{{ $issue->project->name }}</a>
                </div>
                <div class="sidebar-section">
                    <div class="sidebar-title">Created</div>
                    <span class="text-sm text-muted">{{ $issue->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TAG MODAL --}}
<div id="tag-modal" class="modal-backdrop hidden">
    <div class="modal">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <h3 class="modal-title" style="margin:0;">Manage Tags</h3>
            <span class="modal-close" onclick="document.getElementById('tag-modal').classList.add('hidden')">&times;</span>
        </div>
        <div id="tag-toggle-list" style="display:flex;flex-wrap:wrap;gap:.5rem;">
            @foreach ($tags as $tag)
                @php $attached = $issue->tags->contains($tag) @endphp
                <span class="tag-pill {{ $attached ? 'attached' : 'detached' }}"
                      data-tag-uuid="{{ $tag->uuid }}"
                      data-issue-uuid="{{ $issue->uuid }}"
                      style="background:{{ $tag->color }}22;color:{{ $tag->color }};border-color:{{ $tag->color }};"
                      onclick="toggleTag(this)">
                    {{ $tag->name }}
                </span>
            @endforeach
        </div>
        <div style="margin-top:1rem;text-align:right;">
            <button class="btn btn-primary btn-sm" onclick="document.getElementById('tag-modal').classList.add('hidden')">Done</button>
        </div>
    </div>
</div>

{{-- ASSIGNEE MODAL --}}
<div id="assignee-modal" class="modal-backdrop hidden">
    <div class="modal">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <h3 class="modal-title" style="margin:0;">Manage Assignees</h3>
            <span class="modal-close" onclick="document.getElementById('assignee-modal').classList.add('hidden')">&times;</span>
        </div>
        <div id="assignee-toggle-list" style="display:flex;flex-direction:column;gap:.5rem;">
            @foreach ($users as $user)
                @php $assigned = $issue->assignees->contains($user) @endphp
                <label style="display:flex;align-items:center;gap:.75rem;cursor:pointer;padding:.4rem .5rem;border-radius:6px;transition:background .15s;"
                       onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                    <span class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    <span style="flex:1;">{{ $user->name }}</span>
                    <input type="checkbox" {{ $assigned ? 'checked' : '' }}
                           data-user-id="{{ $user->id }}"
                           data-issue-uuid="{{ $issue->uuid }}"
                           onchange="toggleAssignee(this)"
                           style="width:auto;">
                </label>
            @endforeach
        </div>
        <div style="margin-top:1rem;text-align:right;">
            <button class="btn btn-primary btn-sm" onclick="document.getElementById('assignee-modal').classList.add('hidden')">Done</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF      = document.querySelector('meta[name="csrf-token"]').content;
const ISSUE_UUID = '{{ $issue->uuid }}';

// ─── COMMENTS ───────────────────────────────────────────────────────────────
let commentsPage = 1;
let commentsLastPage = 1;

async function loadComments(page = 1, prepend = false) {
    const res  = await fetch(`/comments?filter[issue_id]=${ISSUE_UUID}&page=${page}`, {
        headers: { 'Accept': 'application/json' }
    });
    const json = await res.json();
    commentsLastPage = json.meta?.last_page ?? 1;

    const list = document.getElementById('comment-list');

    if (page === 1) {
        list.innerHTML = '';
        document.getElementById('comments-loading').style.display = 'none';
    }

    if (json.data.length === 0 && page === 1) {
        list.innerHTML = '<p class="text-muted text-sm">No comments yet. Be the first!</p>';
    }

    json.data.forEach(c => {
        const el = buildComment(c);
        if (prepend) list.prepend(el);
        else list.append(el);
    });

    document.getElementById('load-more-wrap').classList.toggle('hidden', commentsPage >= commentsLastPage);
}

function buildComment(c) {
    const div = document.createElement('div');
    div.className = 'comment-item';
    div.innerHTML = `
        <div>
            <span class="comment-author">${escHtml(c.author_name)}</span>
            <span class="comment-time">${escHtml(c.created_at)}</span>
        </div>
        <div class="comment-body">${escHtml(c.body)}</div>`;
    return div;
}

document.getElementById('load-more-btn').addEventListener('click', function () {
    commentsPage++;
    loadComments(commentsPage);
});

document.getElementById('comment-submit').addEventListener('click', async function () {
    const author = document.getElementById('comment-author').value.trim();
    const body   = document.getElementById('comment-body').value.trim();
    const errBox = document.getElementById('comment-errors');

    errBox.className = 'hidden';
    errBox.innerHTML = '';

    const res = await fetch('/comments', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ issue_id: ISSUE_UUID, author_name: author, body })
    });

    const json = await res.json();

    if (!res.ok) {
        const msgs = json.errors
            ? Object.values(json.errors).flat()
            : [json.message ?? 'Something went wrong.'];
        errBox.className = 'alert alert-danger';
        errBox.style.flexDirection = 'column';
        errBox.innerHTML = msgs.map(m => `<div>⚠ ${escHtml(m)}</div>`).join('');
        return;
    }

    const list = document.getElementById('comment-list');
    const placeholder = list.querySelector('.text-muted');
    if (placeholder) placeholder.remove();

    list.prepend(buildComment(json.data));
    document.getElementById('comment-author').value = '';
    document.getElementById('comment-body').value   = '';
});

// ─── TAGS ────────────────────────────────────────────────────────────────────
async function toggleTag(el) {
    const issueUuid = el.dataset.issueUuid;
    const tagUuid   = el.dataset.tagUuid;

    const res  = await fetch(`/issue-tags/${issueUuid}/${tagUuid}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    const json = await res.json();

    el.classList.toggle('attached', json.attached);
    el.classList.toggle('detached', !json.attached);

    refreshTagDisplay();
}

function refreshTagDisplay() {
    const pills = document.querySelectorAll('#tag-toggle-list .tag-pill.attached');
    const display = document.getElementById('issue-tags-display');
    display.innerHTML = '';
    if (pills.length === 0) {
        display.innerHTML = '<span class="text-muted text-sm">No tags</span>';
        return;
    }
    pills.forEach(p => {
        const clone = p.cloneNode(true);
        clone.removeAttribute('onclick');
        display.appendChild(clone);
    });
}

// ─── ASSIGNEES ───────────────────────────────────────────────────────────────
async function toggleAssignee(checkbox) {
    const issueUuid = checkbox.dataset.issueUuid;
    const userId    = checkbox.dataset.userId;

    const res  = await fetch(`/issue-users/${issueUuid}/${userId}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    const json = await res.json();

    checkbox.checked = json.attached;
    refreshAssigneeDisplay();
}

function refreshAssigneeDisplay() {
    const checks  = document.querySelectorAll('#assignee-toggle-list input[type=checkbox]:checked');
    const display = document.getElementById('issue-assignees-display');
    display.innerHTML = '';

    if (checks.length === 0) {
        display.innerHTML = '<span class="text-muted text-sm">No assignees</span>';
        return;
    }

    checks.forEach(cb => {
        const label = cb.closest('label');
        const name  = label.querySelector('span:not(.avatar)').textContent.trim();
        const init  = name.charAt(0).toUpperCase();
        display.innerHTML += `
            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.35rem;">
                <span class="avatar">${escHtml(init)}</span>
                <span class="text-sm">${escHtml(name)}</span>
            </div>`;
    });
}

// Close modals on backdrop click
document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
    backdrop.addEventListener('click', function (e) {
        if (e.target === this) this.classList.add('hidden');
    });
});

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Init
loadComments(1);
</script>
@endpush
