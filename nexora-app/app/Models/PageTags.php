<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PageTags extends Pivot
{
    protected $table = 'page_tags';

    public $incrementing = false;

    protected $fillable = [
        'page_id',
        'tag_id',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tags::class, 'tag_id');
    }
}
