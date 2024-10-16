<?php

use App\Http\Controllers\AIController;
use App\Http\Controllers\BlogPostController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Auth\AuthenticatorController;

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


// Route::middleware(['auth'])->group(function () {
//     Route::get('/', function () {
//         return redirect()->route('post.dashboard');
//     })->name('home');
//     Route::resource('blog-posts', BlogPostController::class);
//     Route::resource('home', BlogPostController::class);
//     Route::resource('dashboard', BlogPostController::class);
// });

// Route::auth();

Route::get('/', function () {
    return redirect()->route('post.dashboard');
})->name('home');

Route::prefix('post')->group(function () {
    Route::get('/dashboard', [BlogPostController::class, 'dashboard'])->name('post.dashboard');
    Route::get('/create', [BlogPostController::class, 'create'])->name('post.create');
    Route::get('/create/{id}', [BlogPostController::class, 'edit'])->name('post.edit');
    Route::get('/show/{id}', [BlogPostController::class, 'show'])->name('post.show');
    Route::post('/update/{id}', [BlogPostController::class, 'update'])->name('post.update');
    Route::get('/duplicate/{id}', [BlogPostController::class, 'duplicate'])->name('post.duplicate');

    //ajax
    Route::get('/ajax-list-post', [BlogPostController::class, 'ajaxListPost'])->name('post.ajax.list');
    Route::get('/ajax-list-status', [BlogPostController::class, 'ajaxListStatus'])->name('post.ajax.status');
    Route::get('/ajax-list-period', [BlogPostController::class, 'ajaxListPeriod'])->name('post.ajax.period');
    Route::get('/ajax-preview-post/{id}', [BlogPostController::class, 'ajaxPreviewPost'])->name('post.ajax.preview');
    Route::delete('/ajax-delete/{id}', [BlogPostController::class, 'ajaxDelete'])->name('post.ajax.delete');
    Route::delete('/ajax-delete-multi', [BlogPostController::class, 'ajaxDeleteMulti'])->name('post.ajax.delete.multi');
    Route::get('/ajax-preview-post', [BlogPostController::class, 'ajaxPreviewPost'])->name('post.ajax.preview');

    Route::get('testCreatePost', [AIController::class, 'testCreatePost2']);
});
//Route::get('/register', [AuthenticatorController::class, 'signUp'])->name('register');
Route::post('/register', [AuthenticatorController::class, 'register'])->name('auth.register');
//Route::post('/login', [AuthenticatorController::class, 'loginValidate'])->name('auth.login');
//Route::get('/login', [AuthenticatorController::class, 'signin'])->name('login');

Route::prefix('ajax-call-ai')->group(function (): void {
    Route::get('/generate-blog-content', [AIController::class, 'generateBlogContent'])->name('ai.generateBlogContent');
    Route::get('/generate-blog-title', [AIController::class, 'generateBlogTitle'])->name('ai.generateBlogTitle');
    Route::get('/generate-blog-outline', [AIController::class, 'generateBlogOutline'])->name('ai.generateBlogOutline');
});

// return redirect()->route('error.page')->with('error', 'Unable to load dashboard');

Route::get('/error', function () {
    return view('errors.error', ['error' => 'Unable to load page']);
})->name('error.page');