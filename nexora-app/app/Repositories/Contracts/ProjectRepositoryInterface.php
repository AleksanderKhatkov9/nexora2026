<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface ProjectRepositoryInterface
{
    public function getAllActiveProject(int $limit = 200): Collection;
    
}
