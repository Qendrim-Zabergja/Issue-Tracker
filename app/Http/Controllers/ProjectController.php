<?php

namespace App\Http\Controllers;

use App\Filters\ProjectFilters;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request, ProjectFilters $filters): View
    {
        $this->authorize('viewAny', Project::class);

        $projects = Project::filter($filters)
            ->withCount('issues')
            ->with('user')
            ->paginate(12)
            ->withQueryString();

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        $this->authorize('create', Project::class);

        return view('projects.create');
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $this->authorize('create', Project::class);

        $project = auth()->user()->projects()->create($request->validated());
        $project = $this->loadRelationships($project, $request);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project, Request $request): View
    {
        $this->authorize('view', $project);

        $project = $this->loadRelationships($project, $request);
        $project->load(['user', 'issues' => fn ($q) => $q->withCount('comments')->with('tags')]);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $project->update($request->validated());
        $project = $this->loadRelationships($project, $request);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    public function delete(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted.');
    }
}
