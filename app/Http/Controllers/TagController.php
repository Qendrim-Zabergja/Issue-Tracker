<?php

namespace App\Http\Controllers;

use App\Filters\TagFilters;
use App\Http\Requests\StoreTagRequest;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(Request $request, TagFilters $filters): View
    {
        $this->authorize('viewAny', Tag::class);

        $tags = Tag::filter($filters)
            ->withCount('issues')
            ->paginate(20)
            ->withQueryString();

        return view('tags.index', compact('tags'));
    }

    public function store(StoreTagRequest $request): RedirectResponse
    {
        $this->authorize('create', Tag::class);

        Tag::create($request->validated());

        return redirect()->route('tags.index')
            ->with('success', 'Tag created.');
    }

    public function delete(Tag $tag): RedirectResponse
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        return redirect()->route('tags.index')
            ->with('success', 'Tag deleted.');
    }
}
