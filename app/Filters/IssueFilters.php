<?php

namespace App\Filters;

class IssueFilters extends QueryFilters
{
    public function search(string $value): void
    {
        $this->builder->where(function ($q) use ($value) {
            $q->where('title', 'like', "%{$value}%")
              ->orWhere('description', 'like', "%{$value}%");
        });
    }

    public function status(string $value): void
    {
        $this->builder->where('status', $value);
    }

    public function priority(string $value): void
    {
        $this->builder->where('priority', $value);
    }

    public function tag(string $value): void
    {
        $this->builder->whereHas('tags', fn ($q) => $q->where('tags.uuid', $value));
    }

    public function project(string $value): void
    {
        $this->builder->whereHas('project', fn ($q) => $q->where('projects.uuid', $value));
    }
}
