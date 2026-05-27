<?php

namespace App\Http\Resources;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageFooterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Page $page */
        $page = $this->resource;

        return [
            'slug' => $page->slug,
            'label' => $page->footer_label ?: $page->menu_label ?: $page->title,
            'type' => $page->menu_type,
            'path' => $page->menu_type === Page::MENU_TYPE_ROUTE ? $page->menuPath() : null,
            'hash' => $page->menu_type === Page::MENU_TYPE_ANCHOR ? $page->menu_hash : null,
            'external_url' => $page->menu_type === Page::MENU_TYPE_EXTERNAL ? $page->link : null,
            'route_name' => $page->menuRouteName(),
            'footer_group' => $page->footer_group,
            'footer_order' => $page->footer_order,
        ];
    }
}
