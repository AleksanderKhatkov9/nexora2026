<?php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Support\Collection;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function getAllActiveProject(int $limit = 200): Collection
    {
        return Project::where('active', 1)
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function getAllActiveWithTags(?string $tagSlug = null, int $limit = 200): Collection
    {
        $query = Project::query()
            ->where('active', true)
            ->with('tags')
            ->orderByDesc('year')
            ->orderByDesc('id');

        if ($tagSlug && $tagSlug !== 'all') {
            $query->whereHas('tags', fn ($builder) => $builder->where('slug', $tagSlug));
        }

        return $query->limit($limit)->get();
    }
}
