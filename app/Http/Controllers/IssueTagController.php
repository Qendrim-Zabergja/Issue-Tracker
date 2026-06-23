<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Issue;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class IssueTagController extends Controller
{
    public function toggle(Issue $issue, Tag $tag): JsonResponse
    {
        $this->authorize('update', $issue);

        $issue->tags()->toggle($tag->id);

        $attached = $issue->tags()->where('tags.id', $tag->id)->exists();

        return response()->json([
            'attached' => $attached,
            'tag'      => new TagResource($tag),
        ]);
    }
}
