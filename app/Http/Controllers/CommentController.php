<?php

namespace App\Http\Controllers;

use App\Filters\CommentFilters;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Issue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request, CommentFilters $filters): JsonResponse
    {
        $this->authorize('viewAny', Comment::class);

        $comments = Comment::filter($filters)
            ->paginate($request->limit ?? 10);

        return CommentResource::collection($comments)->response();
    }

    public function store(StoreCommentRequest $request): JsonResponse
    {
        $this->authorize('create', Comment::class);

        $issue = Issue::where('uuid', $request->validated('issue_id'))->firstOrFail();

        $comment = Comment::create([
            'issue_id'    => $issue->id,
            'author_name' => $request->validated('author_name'),
            'body'        => $request->validated('body'),
        ]);

        $comment = $this->loadRelationships($comment, $request);

        return (new CommentResource($comment))->response()->setStatusCode(201);
    }

    public function delete(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(null, 204);
    }
}
