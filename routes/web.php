<?php

use App\Http\Controllers\AIController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\WordpressController;
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

Route::auth();

Route::get('/', function () {
    return redirect()->route('post.dashboard');
})->name('home');

Route::group(['prefix'=>'post', 'middleware' => ['auth']], function () {
    Route::get('/dashboard', [BlogPostController::class, 'dashboard'])->name('post.dashboard');
    Route::get('/create', [BlogPostController::class, 'create'])->name('post.create');
    Route::get('/create/{id}', [BlogPostController::class, 'edit'])->name('post.edit');
    Route::get('/show/{id}', [BlogPostController::class, 'show'])->name('post.show');
    Route::post('/update/{id}', [BlogPostController::class, 'update'])->name('post.update');
    Route::get('/duplicate/{id}', [BlogPostController::class, 'duplicate'])->name('post.duplicate');
    Route::get('/result/{id}', [BlogPostController::class, 'result'])->name('post.result');
    Route::get('/post-setting/{id}', [BlogPostController::class, 'postSetting'])->name('post.postSetting');
    //ajax
    Route::get('/ajax-list-post', [BlogPostController::class, 'ajaxListPost'])->name('post.ajax.list');
    Route::get('/ajax-list-status', [BlogPostController::class, 'ajaxListStatus'])->name('post.ajax.status');
    Route::get('/ajax-list-period', [BlogPostController::class, 'ajaxListPeriod'])->name('post.ajax.period');
    Route::get('/ajax-preview-post/{id}', [BlogPostController::class, 'ajaxPreviewPost'])->name('post.ajax.preview');
    Route::get('testCreatePost', [AIController::class, 'testCreatePost2']);
    Route::delete('/ajax-delete/{id}', [BlogPostController::class, 'ajaxDelete'])->name('post.ajax.delete');
    Route::delete('/ajax-delete-multi', [BlogPostController::class, 'ajaxDeleteMulti'])->name('post.ajax.delete.multi');
    Route::get('/ajax-preview-post', [BlogPostController::class, 'ajaxPreviewPost'])->name('post.ajax.preview');
    Route::post('/ajax-generate-post', [BlogPostController::class, 'ajaxGeneratePost'])->name('post.ajax.generate');
    Route::get('/ajax-check-post-status', [BlogPostController::class, 'ajaxCheckPostStatus'])->name('post.ajax.check_status');
    Route::post('/ajax-generate-blog-title', [BlogPostController::class, 'ajaxGenerateBlogTitle'])->name('post.ajax.generateBlogTitle');
    Route::post('/ajax-generate-blog-outline', [BlogPostController::class, 'ajaxGenerateBlogOutline'])->name('post.ajax.generateBlogOutline');
    Route::post('/ajax-generate-blog-content', [BlogPostController::class, 'ajaxGenerateBlogContent'])->name('post.ajax.generateBlogContent');
    Route::post('/ajax-update-blog-post', [BlogPostController::class, 'ajaxUpdateBlogPost'])->name('post.ajax.updateBlogPost');
    Route::post('/upload_image', [BlogPostController::class, 'uploadImage'])->name('post.upload_image');
    Route::post('/ajax-generate-seo-setting', [BlogPostController::class, 'ajaxGenerateSeoSetting'])->name('post.ajax.generateSeoSetting');
    Route::post('/ajax-update-tag', [BlogPostController::class, 'ajaxUpdateTag'])->name('post.ajax.updateTag');

    Route::group(['middleware' => [
//        'ajax'
    ]], function () {
        Route::get('/check-wordpress-account', [WordpressController::class, 'checkPlatformAccount'])->name('wordpress.checkWordpressAccount');
        Route::get('/create-platform-account', [WordpressController::class, 'createPlatformAccount'])->name('wordpress.createPlatformAccount');
        Route::get('/publish-to-wordpress', [WordpressController::class, 'publishArticle'])->name('wordpress.publishToWordpress');
    });
});
//Route::get('/register', [AuthenticatorController::class, 'signUp'])->name('register');
Route::post('/register', [AuthenticatorController::class, 'register'])->name('auth.register');
//Route::post('/login', [AuthenticatorController::class, 'loginValidate'])->name('auth.login');
Route::get('/logout', [AuthenticatorController::class, 'logout'])->name('auth.logout');

Route::get('/error', function () {
    return view('errors.error', ['error' => 'Unable to load page']);
})->name('error.page');
