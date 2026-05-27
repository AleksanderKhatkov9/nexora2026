<?php

namespace App\Services;

use App\Http\Resources\BlogPostResource;
use App\Http\Resources\ProjectResource;
use App\Models\BlogPost;
use App\Repositories\Contracts\BlogPostRepositoryInterface;
use App\Repositories\Contracts\PageRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Support\PublicAssetUrl;
use Illuminate\Http\Request;

class SeoService
{
    public function __construct(
        private readonly PageRepositoryInterface $pageRepository,
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly BlogPostRepositoryInterface $blogPostRepository,
    ) {}

    public function resolveForRequest(Request $request): array
    {
        $path = trim($request->path(), '/');

        if ($path === '') {
            return $this->fromPageSlug('home', url('/'));
        }

        if ($path === 'pricing') {
            return $this->fromPageSlug('pricing', url('/pricing'));
        }

        if ($path === 'projects') {
            return $this->fromPageSlug('projects', url('/projects'));
        }

        if ($path === 'news') {
            return $this->fromPageSlug('news', url('/news'));
        }

        if ($path === 'articles') {
            return $this->fromPageSlug('articles', url('/articles'));
        }

        if (preg_match('#^projects/([a-z0-9\-]+)$#', $path, $matches)) {
            return $this->fromProjectSlug($matches[1], url('/projects/'.$matches[1]));
        }

        if (preg_match('#^news/([a-z0-9\-]+)$#', $path, $matches)) {
            return $this->fromBlogPostSlug($matches[1], BlogPost::KIND_NEWS, url('/news/'.$matches[1]));
        }

        if (preg_match('#^articles/([a-z0-9\-]+)$#', $path, $matches)) {
            return $this->fromBlogPostSlug($matches[1], BlogPost::KIND_ARTICLE, url('/articles/'.$matches[1]));
        }

        if (preg_match('#^[a-z0-9\-]+$#', $path)) {
            return $this->fromPageSlug($path, url('/'.$path));
        }

        return $this->defaults(url('/'));
    }

    public function sitemapUrls(): array
    {
        $urls = [];

        $this->pushUrl($urls, url('/'), '1.0', 'daily');
        $this->pushUrl($urls, url('/pricing'), '0.9', 'weekly');
        $this->pushUrl($urls, url('/projects'), '0.9', 'weekly');
        $this->pushUrl($urls, url('/news'), '0.8', 'weekly');
        $this->pushUrl($urls, url('/articles'), '0.8', 'weekly');

        foreach ($this->pageRepository->getSitemapPages() as $page) {
            if (in_array($page->slug, ['home', 'pricing', 'projects', 'news', 'articles'], true)) {
                continue;
            }

            $this->pushUrl(
                $urls,
                url($page->menuPath()),
                '0.7',
                'monthly',
                $page->updated_at
            );
        }

        foreach ($this->projectRepository->getSitemapProjects() as $project) {
            $this->pushUrl(
                $urls,
                url('/projects/'.$project->slug),
                '0.8',
                'monthly',
                $project->updated_at
            );
        }

        foreach ($this->blogPostRepository->getSitemapPosts() as $post) {
            $prefix = $post->kind === BlogPost::KIND_NEWS ? 'news' : 'articles';

            $this->pushUrl(
                $urls,
                url('/'.$prefix.'/'.$post->slug),
                '0.7',
                'weekly',
                $post->updated_at
            );
        }

        return $urls;
    }

    private function fromPageSlug(string $slug, string $canonical): array
    {
        $page = $this->pageRepository->getActiveBySlug($slug);

        if (! $page || ($page->isNavOnly() && $slug !== 'home')) {
            return $this->defaults($canonical);
        }

        return $this->buildMeta(
            title: $page->seo_title ?: $page->title,
            description: $page->seo_description ?: $page->description,
            canonical: $canonical,
            image: PublicAssetUrl::url($page->image),
        );
    }

    private function fromProjectSlug(string $slug, string $canonical): array
    {
        $project = $this->projectRepository->findActiveBySlug($slug);

        if (! $project) {
            return $this->defaults($canonical);
        }

        $resource = (new ProjectResource($project))->resolve();

        return $this->buildMeta(
            title: $resource['seo_title'] ?? $resource['title'],
            description: $resource['seo_description'] ?? $resource['short_description'] ?? null,
            canonical: $canonical,
            image: $resource['cover_image'] ?? null,
        );
    }

    private function fromBlogPostSlug(string $slug, string $kind, string $canonical): array
    {
        $post = $this->blogPostRepository->findActiveBySlugAndKind($slug, $kind);

        if (! $post) {
            return $this->defaults($canonical);
        }

        $resource = (new BlogPostResource($post))->resolve();

        return $this->buildMeta(
            title: $resource['seo_title'] ?? $resource['title'],
            description: $resource['seo_description'] ?? $resource['excerpt'] ?? null,
            canonical: $canonical,
            image: $resource['cover_image'] ?? null,
            ogType: 'article',
        );
    }

    private function defaults(string $canonical): array
    {
        return $this->buildMeta(
            title: config('nexora.seo.default_title'),
            description: config('nexora.seo.default_description'),
            canonical: $canonical,
            image: config('nexora.seo.default_image'),
        );
    }

    private function buildMeta(
        string $title,
        ?string $description,
        string $canonical,
        ?string $image = null,
        string $ogType = 'website',
    ): array {
        $image = $image ?: config('nexora.seo.default_image') ?: asset('favicon.svg');

        return [
            'title' => $title,
            'description' => $description ?: config('nexora.seo.default_description'),
            'canonical' => $canonical,
            'og_title' => $title,
            'og_description' => $description ?: config('nexora.seo.default_description'),
            'og_url' => $canonical,
            'og_image' => $image,
            'og_type' => $ogType,
            'twitter_card' => 'summary_large_image',
        ];
    }

    private function pushUrl(
        array &$urls,
        string $loc,
        string $priority,
        string $changefreq,
        $lastmod = null,
    ): void {
        $urls[] = [
            'loc' => $loc,
            'priority' => $priority,
            'changefreq' => $changefreq,
            'lastmod' => $lastmod?->toAtomString(),
        ];
    }
}
