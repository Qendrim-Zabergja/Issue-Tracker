<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

abstract class Controller
{
    use AuthorizesRequests;

    protected function loadRelationships(Model $model, Request $request): Model
    {
        $with = $request->input('with');

        if (empty($with)) {
            return $model;
        }

        $relations = is_string($with)
            ? array_filter(array_map('trim', explode(',', $with)))
            : (array) $with;

        if (! empty($relations)) {
            try {
                $model->load($relations);
            } catch (\Exception) {
                // ignore unknown relation names
            }
        }

        return $model;
    }
}
