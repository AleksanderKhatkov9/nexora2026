<?php

namespace App\Repositories;

use App\Models\Page;
use App\Repositories\Contracts\PageRepositoryInterface;
use Illuminate\Support\Collection;

class PageRepository implements PageRepositoryInterface
{
    public function getAllActive(int $limit = 200): Collection
    {
        return Page::where('active', 1)
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function getNavigationItems(int $limit = 50): Collection
    {
        return Page::query()
            ->where('active', true)
            ->where('show_in_menu', true)
            ->orderBy('menu_order')
            ->orderBy('id')
            ->limit($limit)
            ->get();
    }

    public function getFooterItems(int $limit = 100): Collection
    {
        return Page::query()
            ->where('active', true)
            ->where('show_in_footer', true)
            ->orderByRaw("FIELD(footer_group, 'sections', 'services', 'legal')")
            ->orderBy('footer_order')
            ->orderBy('id')
            ->limit($limit)
            ->get();
    }

    public function getActiveBySlug(string $slug): ?Page
    {
        return Page::query()
            ->where('active', true)
            ->where('slug', $slug)
            ->first();
    }

    public function getSitemapPages(int $limit = 200): Collection
    {
        return Page::query()
            ->where('active', true)
            ->where('menu_type', Page::MENU_TYPE_ROUTE)
            ->orderBy('slug')
            ->limit($limit)
            ->get();
    }
}
