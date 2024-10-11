<?php

namespace App\Repositories;

use App\Models\BlogPost;
use App\Repositories\Interfaces\BlogPostRepositoryInterface;
use Illuminate\Support\Facades\Log;

class BlogPostRepository implements BlogPostRepositoryInterface
{
    protected $model;

    public function __construct(BlogPost $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        Log::info('Fetching all blog posts');
        return $this->model->all();
    }

    public function getById($id)
    {
        Log::info("Fetching blog post with ID: {$id}");
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        Log::info('Creating a new blog post', $data);
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        Log::info("Updating blog post with ID: {$id}", $data);
        $post = $this->model->findOrFail($id);
        $post->update($data);
        return $post;
    }

    public function delete($id)
    {
        Log::info("Deleting blog post with ID: {$id}");
        $post = $this->model->findOrFail($id);
        return $post->delete();
    }
}
