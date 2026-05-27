<?php

namespace App\Services;

use App\Repositories\Contracts\PageRepositoryInterface;

class PageService
{
    public function __construct(
        private readonly PageRepositoryInterface $pageRepository,
    ) {}

    public function getIndexData(): array
    {
        return [
            'pages' => $this->pageRepository->getAllActive()->toArray(),
        ];
    }
}
