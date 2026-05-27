<?php

namespace App\Http\Controllers\Backend\Project;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $projectService) {}

    public function indexProject(): JsonResponse
    {
        return response()->json($this->projectService->getIndexProjectData(), 200);
    }

    public function portfolio(Request $request): JsonResponse
    {
        $tag = $request->query('tag');

        return response()->json([
            'data' => $this->projectService->getPortfolioData($tag),
        ], 200);
    }
}
