<?php

namespace App\Http\Resources;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageNavResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Page $page */
        $page = $this->resource;

        return [
            'slug' => $page->slug,
            'label' => $page->menu_label ?: $page->title,
            'type' => $page->menu_type,
            'path' => $page->menu_type === Page::MENU_TYPE_ROUTE ? $page->menuPath() : null,
            'hash' => $page->menu_type === Page::MENU_TYPE_ANCHOR ? $page->menu_hash : null,
            'external_url' => $page->menu_type === Page::MENU_TYPE_EXTERNAL ? $page->link : null,
            'route_name' => $page->menuRouteName(),
        ];
    }
}
