<?php

namespace App\Http\Controllers;

use App\Services\BlogPostService;
use Auth;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    protected $service;

    public function __construct(BlogPostService $service)
    {
        $this->service = $service;
    }

    public function dashboard(Request $request)
    {
        $platform_id = $request->get('platform_id') ?? null;
        return view('blog-posts.index', compact('platform_id'));
    }

    public function ajaxListPost(Request $request)
    {
        if ($request->ajax()) {
            $platform_id = $request->get('platform_id') ?? null;
            $user_id = Auth::id();

            $posts = $this->service->getAllPosts(['platform_id' => $platform_id, 'user_id' => $user_id]);

            $view = view('partials.ajax.list_post', compact('posts'))->render();

            return response()->json(['html' => $view]);
        }
    }
}
