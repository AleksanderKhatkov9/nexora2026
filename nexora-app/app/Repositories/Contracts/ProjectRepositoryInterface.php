<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface ProjectRepositoryInterface
{
    public function getAllActiveProject(int $limit = 200): Collection;

    public function getAllActiveWithTags(?string $tagSlug = null, int $limit = 200): Collection;
}
