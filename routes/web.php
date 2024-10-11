<?php

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
    Route::get('/ajax-list-post', [BlogPostController::class, 'ajaxListPost'])->name('post.ajax.list');
});