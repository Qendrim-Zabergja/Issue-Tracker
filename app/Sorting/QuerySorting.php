<?php

namespace App\Sorting;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class QuerySorting
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        if (! $this->request->sort) {
            return $builder->orderBy('created_at', 'desc');
        }

        $sort = $this->request->sort;
        $direction = 'asc';

        if (str_starts_with($sort, '!')) {
            $sort = substr($sort, 1);
            $direction = 'desc';
        }

        $table = $builder->getModel()->getTable();

        if (! in_array($sort, Schema::getColumnListing($table))) {
            return $builder;
        }

        return $builder->orderBy($sort, $direction);
    }
}
