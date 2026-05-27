<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'image',
        'link',
        'active',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'active' => 'boolean',
        'content' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tags::class, 'page_tags', 'page_id', 'tag_id')
            ->withTimestamps();
    }
}
