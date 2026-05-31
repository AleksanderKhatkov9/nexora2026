<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'show_in_footer',
        'footer_group',
        'footer_order',
        'footer_label',
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

    public const FOOTER_GROUP_SECTIONS = 'sections';

    public const FOOTER_GROUP_SERVICES = 'services';

    public const FOOTER_GROUP_LEGAL = 'legal';

    public const FOOTER_GROUPS = [
        self::FOOTER_GROUP_SECTIONS => 'Разделы',
        self::FOOTER_GROUP_SERVICES => 'Услуги',
        self::FOOTER_GROUP_LEGAL => 'Правовая информация',
    ];

    protected $casts = [
        'active' => 'boolean',
        'show_in_menu' => 'boolean',
        'show_in_footer' => 'boolean',
        'menu_order' => 'integer',
        'footer_order' => 'integer',
        'content' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function menuPath(): string
    {
        return match ($this->slug) {
            'home' => '/',
            'projects' => '/projects',
            'reviews' => '/reviews',
            'news' => '/news',
            'articles' => '/articles',
            default => '/'.$this->slug,
        };
    }

    public function menuRouteName(): string
    {
        return match ($this->slug) {
            'home' => 'home',
            'projects' => 'projects',
            'pricing' => 'pricing',
            'reviews' => 'reviews',
            'news' => 'news',
            'articles' => 'articles',
            default => 'page',
        };
    }

    public function isNavOnly(): bool
    {
        return in_array($this->menu_type, [self::MENU_TYPE_ANCHOR, self::MENU_TYPE_EXTERNAL], true);
    }
}
