<?php

namespace App\Http\Controllers;

use App\Services\BlogPostService;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    protected $service;

    public function __construct(BlogPostService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->getAllPosts());
    }

    public function show($id)
    {
        return response()->json($this->service->getPostById($id));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        return response()->json($this->service->createPost($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        return response()->json($this->service->updatePost($id, $data));
    }

    public function destroy($id)
    {
        return response()->json($this->service->deletePost($id));
    }
}
