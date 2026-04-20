<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Admin\ImageRemoveController;

/*
|--------------------------------------------------------------------------
| Public Routes (Frontend)
|--------------------------------------------------------------------------
| Route yang bisa diakses oleh semua pengunjung.
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/post/{slug}', [PostController::class, 'show'])->name('post.show');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| Route untuk login dan logout.
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes (Authenticated)
|--------------------------------------------------------------------------
| Route yang hanya bisa diakses setelah login.
| Prefix: /admin
| Name prefix: admin.
*/

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Posts
    Route::resource('posts', AdminPostController::class)->except(['show']);

    // CRUD Categories
    Route::resource('categories', AdminCategoryController::class)->except(['show']);

    // Upload gambar dari Trix Editor
    Route::post('/upload-image', [ImageUploadController::class, 'store'])->name('upload.image');

    // Remove gambar dari Trix Editor
    Route::post('/remove-image', [ImageRemoveController::class, 'delete'])->name('remove.image');
});
