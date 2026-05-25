<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectTag extends Pivot
{
    protected $table = 'project_tags';

    public $incrementing = false;

    protected $fillable = [
        'project_id',
        'tag_id',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tags::class, 'tag_id');
    }
}
