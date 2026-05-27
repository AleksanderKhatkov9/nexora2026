<?php

use App\Http\Controllers\Backend\Project\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Page\IndexController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('page')->controller(IndexController::class)->group(function () {
    Route::get('/', 'indexPage')->name('page.index');
    Route::get('/home', 'showHome')->name('page.home');
});

Route::prefix('project')->controller(ProjectController::class)->group(function () {
    Route::get('/', 'indexProject')->name('project.index');
});

Route::get('/projects', [ProjectController::class, 'portfolio'])->name('projects.portfolio');
