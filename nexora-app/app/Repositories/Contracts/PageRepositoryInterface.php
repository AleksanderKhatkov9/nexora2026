<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface PageRepositoryInterface
{
    public function getAllActive(int $limit = 200): Collection;

    public function getActiveBySlug(string $slug): ?\App\Models\Page;
}
