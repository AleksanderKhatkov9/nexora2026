<?php

namespace App\Repositories;

use App\Models\BlogPost;
use App\Repositories\Contracts\BlogPostRepositoryInterface;
use Illuminate\Support\Collection;

class BlogPostRepository implements BlogPostRepositoryInterface
{
    public function getActiveByKind(string $kind, int $limit = 200): Collection
    {
        return BlogPost::query()
            ->where('active', true)
            ->where('kind', $kind)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function findActiveBySlugAndKind(string $slug, string $kind): ?BlogPost
    {
        return BlogPost::query()
            ->where('active', true)
            ->where('kind', $kind)
            ->where('slug', $slug)
            ->first();
    }

    public function getSitemapPosts(int $limit = 500): Collection
    {
        return BlogPost::query()
            ->where('active', true)
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get(['slug', 'kind', 'updated_at']);
    }
}
