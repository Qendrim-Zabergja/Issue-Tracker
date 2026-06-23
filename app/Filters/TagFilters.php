<?php

namespace App\Filters;

class TagFilters extends QueryFilters
{
    public function search(string $value): void
    {
        $this->builder->where('name', 'like', "%{$value}%");
    }
}
