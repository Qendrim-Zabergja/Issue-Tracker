<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class IssueUserController extends Controller
{
    public function toggle(Issue $issue, User $user): JsonResponse
    {
        $this->authorize('update', $issue);

        $issue->assignees()->toggle($user->id);

        $attached = $issue->assignees()->where('users.id', $user->id)->exists();

        return response()->json([
            'attached' => $attached,
            'user'     => new UserResource($user),
        ]);
    }
}
