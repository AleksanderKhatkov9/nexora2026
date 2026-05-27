<?php

use App\Http\Controllers\Backend\Project\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Page\IndexController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('page')->controller(IndexController::class)->group(function () {
    Route::get('/', 'indexPage')->name('page.index');
});

Route::prefix('project')->controller(ProjectController::class)->group(function () {
    Route::get('/', 'indexProject')->name('project.index');
});
