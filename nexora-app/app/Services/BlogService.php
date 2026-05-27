<?php

namespace App\Services;

use App\Http\Resources\BlogPostResource;
use App\Models\BlogPost;
use App\Repositories\Contracts\BlogPostRepositoryInterface;
use App\Repositories\Contracts\PageRepositoryInterface;

class BlogService
{
    public function __construct(
        private readonly BlogPostRepositoryInterface $blogPostRepository,
        private readonly PageRepositoryInterface $pageRepository,
    ) {}

    public function getFeedData(string $kind): array
    {
        $pageSlug = $kind === BlogPost::KIND_NEWS ? 'news' : 'articles';
        $page = $this->pageRepository->getActiveBySlug($pageSlug);

        return [
            'page' => $page ? [
                'title' => $page->title,
                'description' => $page->description,
                'seo_title' => $page->seo_title,
                'seo_description' => $page->seo_description,
            ] : null,
            'posts' => BlogPostResource::collection(
                $this->blogPostRepository->getActiveByKind($kind)
            )->resolve(),
        ];
    }

    public function getPostBySlug(string $slug, string $kind): ?array
    {
        $post = $this->blogPostRepository->findActiveBySlugAndKind($slug, $kind);

        if (! $post) {
            return null;
        }

        return (new BlogPostResource($post))->resolve();
    }
}
