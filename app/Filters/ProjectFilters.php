<?php

namespace App\Filters;

class ProjectFilters extends QueryFilters
{
    public function search(string $value): void
    {
        $this->builder->where(function ($q) use ($value) {
            $q->where('name', 'like', "%{$value}%")
              ->orWhere('description', 'like', "%{$value}%");
        });
    }
}
