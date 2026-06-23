<?php

namespace App\Http\Controllers;

use App\Filters\IssueFilters;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IssueController extends Controller
{
    public function index(Request $request, IssueFilters $filters): View
    {
        $this->authorize('viewAny', Issue::class);

        $issues = Issue::filter($filters)
            ->with(['project', 'tags'])
            ->paginate(15)
            ->withQueryString();

        $tags = Tag::orderBy('name')->get();

        return view('issues.index', compact('issues', 'tags'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Issue::class);

        $projects = Project::orderBy('name')->get();
        $selectedProject = $request->project_id
            ? Project::where('uuid', $request->project_id)->first()
            : null;

        return view('issues.create', compact('projects', 'selectedProject'));
    }

    public function store(StoreIssueRequest $request): RedirectResponse
    {
        $this->authorize('create', Issue::class);

        $project = Project::where('uuid', $request->project_id)->firstOrFail();
        $issue = $project->issues()->create($request->validated());
        $issue = $this->loadRelationships($issue, $request);

        return redirect()->route('issues.show', $issue)
            ->with('success', 'Issue created successfully.');
    }

    public function show(Issue $issue, Request $request): View
    {
        $this->authorize('view', $issue);

        $issue = $this->loadRelationships($issue, $request);
        $issue->load(['project', 'tags', 'assignees']);

        $tags     = Tag::orderBy('name')->get();
        $users    = User::orderBy('name')->get();
        $comments = $issue->comments()->paginate(10);

        return view('issues.show', compact('issue', 'tags', 'users', 'comments'));
    }

    public function edit(Issue $issue): View
    {
        $this->authorize('update', $issue);

        $projects = Project::orderBy('name')->get();

        return view('issues.edit', compact('issue', 'projects'));
    }

    public function update(UpdateIssueRequest $request, Issue $issue): RedirectResponse
    {
        $this->authorize('update', $issue);

        $issue->update($request->validated());
        $issue = $this->loadRelationships($issue, $request);

        return redirect()->route('issues.show', $issue)
            ->with('success', 'Issue updated successfully.');
    }

    public function destroy(Issue $issue): RedirectResponse
    {
        $this->authorize('delete', $issue);

        $project = $issue->project;
        $issue->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', 'Issue deleted.');
    }
}
