<?php

namespace App\Http\Controllers;

use App\Services\BlogPostService;
use Auth;
use Illuminate\Http\Request;
use Log;

class BlogPostController extends Controller
{
    protected $service;

    public function __construct(BlogPostService $service)
    {
        $this->service = $service;
    }

    public function dashboard(Request $request)
    {
        try {
            $platform_id = $request->get('platform_id') ?? null;
            $blog_posts = $this->service->getAllPosts(['platform_id' => $platform_id]);
            trackInfo('Loading blog posts for platform', ['platform_id' => $platform_id]);
            return view('blog_posts.list', compact('platform_id', 'blog_posts'));
        } catch (\Exception $e) {
            trackError('Error loading dashboard', ['error' => $e->getMessage()]);
            return redirect()->route('error.page')->with('error', 'Unable to load dashboard');
        }
        
    }

    public function ajaxListPost(Request $request)
    {
        if ($request->ajax()) {
            $platform_id = $request->get('platform_id') ?? null;
            $user_id = Auth::id();
            $search = $request->get('search') ?? null;
            $status = $request->get('status') ?? null;

            $params = [
                'platform_id' => $platform_id, 
                'user_id' => $user_id,
                'search' => $search,
                'status' => $status,
            ];

            Log::info('AJAX list post requested', ['params' => $params]);

            $posts = $this->service->getAllPosts($params);

            $view = view('partials.ajax.list_post', compact('posts'))->render();

            return response()->json(['html' => $view]);
        }
    }

    public function create(Request $request)
    {
        $platform_id = $request->get('platform_id') ?? null;
        $data  = [
            'platform_id' => $platform_id,
            'status' => 'draft',
            'user_id' => Auth::id(),
        ];
        Log::info('Creating post', ['data' => $data]);
        //create post auto save then redirect to edit post
        $post = $this->service->createPost($data);
        return redirect()->route('posts.edit', ['id' => $post->id]);
    }

    public function edit($id)
    {
        Log::info('Editing post', ['post_id' => $id]);
        $post = $this->service->getPostById($id);
        return view('blog_posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        Log::info('Updating post', ['post_id' => $id, 'data' => $data]);
        $post = $this->service->updatePost($id, $data);
        return redirect()->route('posts.edit', ['id' => $post->id]);
    }

    public function delete($id)
    {
        Log::info('Deleting post', ['post_id' => $id]);
        $this->service->deletePost($id);
        return redirect()->route('posts.dashboard');
    }

    public function deleteMulti(Request $request)
    {
        $ids = $request->get('ids');
        
        Log::info('Deleting all posts', ['post_ids' => $ids]);

        $this->service->deleteMultiPosts($ids);

        redirect()->route('posts.dashboard');
    }

    public function ajaxPreviewPost(Request $request, $id)
    {
        if ($request->ajax()) {
            Log::info('AJAX preview post requested', ['post_id' => $id]);
            $post = $this->service->getPostById($id);
            $view = view('partials.ajax.preview_post', compact('post'))->render();
            return response()->json(['html' => $view]);
        }
    }

}
