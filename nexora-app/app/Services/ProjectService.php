<?php

namespace App\Services;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\TagResource;
use App\Repositories\Contracts\PageRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly TagRepositoryInterface $tagRepository,
        private readonly PageRepositoryInterface $pageRepository,
    ) {}

    public function getIndexProjectData(): array
    {
        return [
            'projects' => ProjectResource::collection(
                $this->projectRepository->getAllActiveProject()
            )->resolve(),
        ];
    }

    public function getPortfolioData(?string $tagSlug = null): array
    {
        $tags = $this->tagRepository->getAll();
        $page = $this->pageRepository->getActiveBySlug('projects');

        return [
            'page' => $page ? [
                'title' => $page->title,
                'description' => $page->description,
                'seo_title' => $page->seo_title,
                'seo_description' => $page->seo_description,
            ] : null,
            'tags' => TagResource::collection($tags)->resolve(),
            'projects' => ProjectResource::collection(
                $this->projectRepository->getAllActiveWithTags($tagSlug)
            )->resolve(),
        ];
    }
}
