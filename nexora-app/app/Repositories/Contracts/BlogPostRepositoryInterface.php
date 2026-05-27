<?php

namespace App\Repositories\Contracts;

use App\Models\BlogPost;
use Illuminate\Support\Collection;

interface BlogPostRepositoryInterface
{
    public function getActiveByKind(string $kind, int $limit = 200): Collection;

    public function findActiveBySlugAndKind(string $slug, string $kind): ?BlogPost;

    public function getSitemapPosts(int $limit = 500): Collection;
}
