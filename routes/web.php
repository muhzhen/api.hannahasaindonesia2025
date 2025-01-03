<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return redirect('/admin/login');
});

Route::get('/posts', [PostController::class, 'index']);
Route::get('/search',[PostController::class, 'search']);
Route::get('/post/{slug}',[PostController::class, 'show']);
