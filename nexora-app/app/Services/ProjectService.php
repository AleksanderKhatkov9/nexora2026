<?php

namespace App\Services;

use App\Repositories\Contracts\ProjectRepositoryInterface;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
    ) {}

    public function getIndexProjectData(): array
    {
        return [
            'projects' => $this->projectRepository->getAllActiveProject()->toArray(),
        ];
    }
}
