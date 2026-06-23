<?php

namespace App\Filters;

use App\Sorting\QuerySorting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QueryFilters
{
    protected Request $request;
    protected Builder $builder;
    protected QuerySorting $sorting;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->sorting = new QuerySorting($request);
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->filters() as $name => $value) {
            if (! method_exists($this, $name)) {
                continue;
            }

            if (is_array($value) || strlen((string) $value)) {
                $this->$name($value);
            }
        }

        return $this->sorting->apply($this->builder);
    }

    public function filters(): array
    {
        $filter = $this->request->filter;

        if (is_array($filter)) {
            return array_filter($filter, fn ($v) => $v !== null && $v !== '');
        }

        return [];
    }
}
