<?php

namespace App\Filters;

class CommentFilters extends QueryFilters
{
    public function search(string $value): void
    {
        $this->builder->where(function ($q) use ($value) {
            $q->where('author_name', 'like', "%{$value}%")
              ->orWhere('body', 'like', "%{$value}%");
        });
    }
}
