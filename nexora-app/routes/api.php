<?php

use App\Http\Controllers\Backend\Order\OrderController;
use App\Http\Controllers\Backend\Project\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Page\IndexController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('page')->controller(IndexController::class)->group(function () {
    Route::get('/', 'indexPage')->name('page.index');
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
Route::get('/projects/{id}', [ProjectController::class, 'show'])->whereNumber('id')->name('projects.show');

Route::post('/orders', [OrderController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('orders.store');
