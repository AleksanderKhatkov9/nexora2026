<?php

namespace App\Http\Controllers\Backend\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Services\BlogService;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $blogService) {}

    public function newsFeed(): JsonResponse
    {
        return response()->json([
            'data' => $this->blogService->getFeedData(BlogPost::KIND_NEWS),
        ]);
    }

    public function articlesFeed(): JsonResponse
    {
        return response()->json([
            'data' => $this->blogService->getFeedData(BlogPost::KIND_ARTICLE),
        ]);
    }

    public function showNews(string $slug): JsonResponse
    {
        return $this->showPost($slug, BlogPost::KIND_NEWS);
    }

    public function showArticle(string $slug): JsonResponse
    {
        return $this->showPost($slug, BlogPost::KIND_ARTICLE);
    }

    private function showPost(string $slug, string $kind): JsonResponse
    {
        $data = $this->blogService->getPostBySlug($slug, $kind);

        if (! $data) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json(['data' => $data]);
    }
}
