<?php

namespace App\Services;

use App\Http\Resources\PageFooterResource;
use App\Http\Resources\PageNavResource;
use App\Http\Resources\PageResource;
use App\Repositories\Contracts\PageRepositoryInterface;

class PageService
{
    public function __construct(
        private readonly PageRepositoryInterface $pageRepository,
    ) {}

    public function getHomeData(): ?array
    {
        return $this->getPageDataBySlug('home');
    }

    public function getPricingData(): ?array
    {
        return $this->getPageDataBySlug('pricing');
    }

    public function getNavigationData(): array
    {
        return PageNavResource::collection(
            $this->pageRepository->getNavigationItems()
        )->resolve();
    }

    public function getFooterData(): array
    {
        return PageFooterResource::collection(
            $this->pageRepository->getFooterItems()
        )->resolve();
    }

    public function getPageBySlug(string $slug): ?array
    {
        $page = $this->pageRepository->getActiveBySlug($slug);

        if (! $page || $page->isNavOnly()) {
            return null;
        }

        return (new PageResource($page))->resolve();
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
