<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    public const KIND_NEWS = 'news';

    public const KIND_ARTICLE = 'article';

    public const KINDS = [
        self::KIND_NEWS => 'Новость',
        self::KIND_ARTICLE => 'Статья',
    ];

    protected $fillable = [
        'slug',
        'title',
        'kind',
        'excerpt',
        'content',
        'cover_image',
        'author',
        'published_at',
        'active',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isNews(): bool
    {
        return $this->kind === self::KIND_NEWS;
    }

    public function isArticle(): bool
    {
        return $this->kind === self::KIND_ARTICLE;
    }

    public function publicPath(): string
    {
        $prefix = $this->isNews() ? 'news' : 'articles';

        return '/'.$prefix.'/'.$this->slug;
    }
}
