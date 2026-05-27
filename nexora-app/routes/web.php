<?php

use App\Http\Controllers\Backend\Order\OrderController;
use App\Http\Controllers\Backend\Page\IndexController;
use Illuminate\Support\Facades\Route;

Route::post('/api/orders', [OrderController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('orders.store');

Route::get('/sitemap.xml', \App\Http\Controllers\SitemapController::class)->name('sitemap');

Route::get('/', IndexController::class)->name('home');
Route::get('/pricing', IndexController::class)->name('pricing');
Route::get('/projects/{any?}', IndexController::class)->where('any', '.*')->name('projects');
Route::get('/{slug}', IndexController::class)
    ->where('slug', '[a-z0-9\-]+')
    ->name('page');
