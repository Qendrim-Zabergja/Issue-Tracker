<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Issue;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function index(Issue $issue): JsonResponse
    {
        $this->authorize('view', $issue);

        $comments = $issue->comments()->paginate(10);

        return CommentResource::collection($comments)->response();
    }

    public function store(StoreCommentRequest $request, Issue $issue): JsonResponse
    {
        $this->authorize('view', $issue);

        $comment = $issue->comments()->create($request->validated());

        return (new CommentResource($comment))->response()->setStatusCode(201);
    }
}
