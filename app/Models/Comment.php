<?php

namespace App\Models;

use App\Filters\Filterable;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, HasUuid, SoftDeletes, Filterable;

    protected $fillable = ['issue_id', 'author_name', 'body'];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }
}
