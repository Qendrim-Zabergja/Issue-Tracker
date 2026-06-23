<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->uuid,
            'name'   => $this->name,
            'color'  => $this->color,
            'issues' => IssueResource::collection($this->whenLoaded('issues')),
        ];
    }
}
