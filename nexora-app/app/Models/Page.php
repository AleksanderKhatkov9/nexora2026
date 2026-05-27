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
        'show_in_menu',
        'menu_order',
        'menu_label',
        'menu_type',
        'menu_hash',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    public const MENU_TYPE_ROUTE = 'route';

    public const MENU_TYPE_ANCHOR = 'anchor';

    public const MENU_TYPE_EXTERNAL = 'external';

    public const MENU_TYPES = [
        self::MENU_TYPE_ROUTE => 'Страница сайта',
        self::MENU_TYPE_ANCHOR => 'Якорь на главной',
        self::MENU_TYPE_EXTERNAL => 'Внешняя ссылка',
    ];

    protected $casts = [
        'active' => 'boolean',
        'show_in_menu' => 'boolean',
        'menu_order' => 'integer',
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

    public function menuPath(): string
    {
        return match ($this->slug) {
            'home' => '/',
            'projects' => '/projects',
            default => '/'.$this->slug,
        };
    }

    public function menuRouteName(): string
    {
        return match ($this->slug) {
            'home' => 'home',
            'projects' => 'projects',
            'pricing' => 'pricing',
            default => 'page',
        };
    }

    public function isNavOnly(): bool
    {
        return in_array($this->menu_type, [self::MENU_TYPE_ANCHOR, self::MENU_TYPE_EXTERNAL], true);
    }
}
