<?php

namespace App\Http\Controllers\Backend\Page;

use App\Http\Controllers\Controller;
use App\Services\PageService;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    public function __construct(private readonly PageService $pageService) {}

    public function __invoke()
    {
        return view('index');
    }

    public function indexPage(): JsonResponse
    {
        return response()->json($this->pageService->getIndexData(), 200);
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

    public function navigation(): JsonResponse
    {
        return response()->json([
            'data' => $this->pageService->getNavigationData(),
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
