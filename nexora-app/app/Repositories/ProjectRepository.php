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
}
