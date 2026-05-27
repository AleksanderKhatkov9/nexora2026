<?php

namespace App\Http\Controllers\Backend\Page;

use App\Http\Controllers\Controller;
use App\Services\PageService;
use App\Support\PortfolioData;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    public function __construct(private readonly PageService $pageService) {}

    public function __invoke()
    {
        return view('backend.page.index');
    }

    public function index_projects()
    {
        return view('projects', [
            'tags' => PortfolioData::tags(),
            'projects' => PortfolioData::projects(),
        ]);
    }

    public function indexPage(): JsonResponse
    {
        return response()->json($this->pageService->getIndexData(), 200);
    }
}
