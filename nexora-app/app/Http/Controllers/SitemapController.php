<?php

namespace App\Http\Controllers;

use App\Services\SeoService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(SeoService $seoService): Response
    {
        $urls = $seoService->sitemapUrls();

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
