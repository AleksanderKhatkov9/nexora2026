<?php

namespace App\Http\Controllers\Backend\Page;

use App\Http\Controllers\Controller;
use App\Services\PageService;
use App\Services\SeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __construct(
        private readonly PageService $pageService,
        private readonly SeoService $seoService,
    ) {}

    public function __invoke(Request $request)
    {
        return view('index', [
            'seo' => $this->seoService->resolveForRequest($request),
        ]);
    }

    public function showHome(): JsonResponse
    {
        $data = $this->pageService->getHomeData();

        if (! $data) {
            return response()->json(['message' => 'Home page not found'], 404);
        }

        return response()->json(['data' => $data], 200);
    }

    public function showPricing(): JsonResponse
    {
        $data = $this->pageService->getPricingData();

        if (! $data) {
            return response()->json(['message' => 'Pricing page not found'], 404);
        }

        return response()->json(['data' => $data], 200);
    }

    public function showReviews(): JsonResponse
    {
        $data = $this->pageService->getReviewsData();

        if (! $data) {
            return response()->json(['message' => 'Reviews page not found'], 404);
        }

        return response()->json(['data' => $data], 200);
    }

    public function navigation(): JsonResponse
    {
        return response()->json([
            'data' => $this->pageService->getNavigationData(),
        ], 200);
    }

    public function footer(): JsonResponse
    {
        return response()->json([
            'data' => $this->pageService->getFooterData(),
        ], 200);
    }

    public function show(string $slug): JsonResponse
    {
        $data = $this->pageService->getPageBySlug($slug);

        if (! $data) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        return response()->json(['data' => $data], 200);
    }
}
