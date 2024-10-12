<?php

use App\Http\Controllers\AIController;
use App\Http\Controllers\BlogPostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view(view: 'welcome');
});


Route::resource('blog-posts', BlogPostController::class);

Route::prefix('post')->group(function () {
    Route::get('/dashboard', [BlogPostController::class, 'dashboard'])->name('post.dashboard');
    Route::get('/create', [BlogPostController::class, 'create'])->name('post.create');
    Route::get('/edit/{id}', [BlogPostController::class, 'edit'])->name('post.edit');
    Route::post('/update/{id}', [BlogPostController::class, 'update'])->name('post.update');
    Route::delete('/delete/{id}', [BlogPostController::class, 'delete'])->name('post.delete');
    Route::delete('/delete-multi', [BlogPostController::class, 'deleteMulti'])->name('post.delete.multi');
    //ajax
    Route::get('/ajax-list-post', [BlogPostController::class, 'ajaxListPost'])->name('post.ajax.list');
    Route::get('/ajax-preview-post/{id}', [BlogPostController::class, 'ajaxPreviewPost'])->name('post.ajax.preview');
    Route::get('testCreatePost', [AIController::class, 'testCreatePost2']);
});

Route::prefix('ajax-call-ai')->group(function () {
   
});