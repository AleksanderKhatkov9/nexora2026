<?php

use App\Http\Controllers\Backend\Page\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('home');
Route::get('/projects', IndexController::class)->name('projects');
