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
}
