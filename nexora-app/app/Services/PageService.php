<?php

namespace App\Services;

use App\Http\Resources\PageResource;
use App\Repositories\Contracts\PageRepositoryInterface;

class PageService
{
    public function __construct(
        private readonly PageRepositoryInterface $pageRepository,
    ) {}

    public function getIndexData(): array
    {
        return [
            'pages' => PageResource::collection($this->pageRepository->getAllActive())->resolve(),
        ];
    }

    public function getHomeData(): ?array
    {
        return $this->getPageDataBySlug('home');
    }

    public function getPricingData(): ?array
    {
        return $this->getPageDataBySlug('pricing');
    }

    private function getPageDataBySlug(string $slug): ?array
    {
        $page = $this->pageRepository->getActiveBySlug($slug);

        if (! $page) {
            return null;
        }

        return (new PageResource($page))->resolve();
    }
}
