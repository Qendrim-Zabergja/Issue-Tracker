<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IssueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->uuid,
            'title'       => $this->title,
            'description' => $this->description,
            'status'      => $this->status,
            'priority'    => $this->priority,
            'due_date'    => $this->due_date?->toDateString(),
            'created_at'  => $this->created_at->toDateTimeString(),
            'project'     => new ProjectResource($this->whenLoaded('project')),
            'tags'        => TagResource::collection($this->whenLoaded('tags')),
            'assignees'   => UserResource::collection($this->whenLoaded('assignees')),
            'comments'    => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
