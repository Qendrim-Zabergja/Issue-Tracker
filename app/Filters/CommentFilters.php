<?php

namespace App\Filters;

use App\Models\Issue;

class CommentFilters extends QueryFilters
{
    public function issue_id(string $value): void
    {
        $issue = Issue::where('uuid', $value)->first();

        if ($issue) {
            $this->builder->where('issue_id', $issue->id);
        }
    }

    public function search(string $value): void
    {
        $this->builder->where(function ($q) use ($value) {
            $q->where('author_name', 'like', "%{$value}%")
              ->orWhere('body', 'like', "%{$value}%");
        });
    }
}
