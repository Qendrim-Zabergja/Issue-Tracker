<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->uuid,
            'name'        => $this->name,
            'description' => $this->description,
            'start_date'  => $this->start_date?->toDateString(),
            'deadline'    => $this->deadline?->toDateString(),
            'created_at'  => $this->created_at->toDateTimeString(),
            'user'        => new UserResource($this->whenLoaded('user')),
            'issues'      => IssueResource::collection($this->whenLoaded('issues')),
        ];
    }
}
