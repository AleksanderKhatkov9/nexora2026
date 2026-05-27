<?php

use App\Http\Controllers\Backend\Blog\BlogController;
use App\Http\Controllers\Backend\Project\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Page\IndexController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('page')->controller(IndexController::class)->group(function () {
    Route::get('/navigation', 'navigation')->name('page.navigation');
    Route::get('/footer', 'footer')->name('page.footer');
    Route::get('/home', 'showHome')->name('page.home');
    Route::get('/pricing', 'showPricing')->name('page.pricing');
    Route::get('/{slug}', 'show')->name('page.show');
});

Route::prefix('project')->controller(ProjectController::class)->group(function () {
    Route::get('/', 'indexProject')->name('project.index');
});

Route::get('/projects', [ProjectController::class, 'portfolio'])->name('projects.portfolio');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])
    ->where('slug', '[a-z0-9\-]+')
    ->name('projects.show');

Route::get('/news', [BlogController::class, 'newsFeed'])->name('blog.news');
Route::get('/news/{slug}', [BlogController::class, 'showNews'])
    ->where('slug', '[a-z0-9\-]+')
    ->name('blog.news.show');
Route::get('/articles', [BlogController::class, 'articlesFeed'])->name('blog.articles');
Route::get('/articles/{slug}', [BlogController::class, 'showArticle'])
    ->where('slug', '[a-z0-9\-]+')
    ->name('blog.articles.show');
