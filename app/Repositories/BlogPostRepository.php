<?php

namespace App\Repositories;

use App\Models\BlogPost;
use App\Models\BlogPostHistory;
use App\Repositories\Interfaces\BlogPostRepositoryInterface;
use Illuminate\Support\Facades\Log;

class BlogPostRepository implements BlogPostRepositoryInterface
{
    protected $model;
    protected $perPage = 10;
    public function __construct(BlogPost $model)
    {
        $this->model = $model;
    }

    public function getAll($params)
    {
        $query = $this->model->query();
        if (isset($params['platform_id']) && !empty($params['platform_id'])) {
            $query->join('post_platforms AS pp', 'pp.blog_post_id', '=', 'blog_posts.id');
            $query->where('pp.platform_account_id', $params['platform_id']);
        }
        if (isset($params['user_id']) && !empty($params['user_id'])) {
            $query->where('blog_posts.user_id', $params['user_id']);
        }
        if (isset($params['status']) && !empty($params['status'])) {
            $query->where('blog_posts.status', $params['status']);
        }
        if (isset($params['search']) && !empty($params['search'])) {
            $query->where('blog_posts.title', 'like', '%' . $params['search'] . '%');
        }
        $query->orderBy('blog_posts.id', 'desc');

        return $query->paginate($this->perPage);
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    { 
        $created = $this->model->create($data);
        
       $this->createHistory([
            'blog_post_id' => $created->id,
            'title' => $created->title,
            'content' => $created->content,
            'short_content' => $created->short_content,
            'status' => $created->status,
            'user_id' => $created->user_id,
        ]);

        return $created;
    }

    public function update($id, array $data)
    {


        $post = $this->model->findOrFail($id);
        $post->update($data);

        $this->createHistory([
            'blog_post_id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'short_content' => $post->short_content,
            'status' => $post->status,
            'user_id' => $post->user_id,
        ]);

        return $post;
    }

    public function delete($id)
    {
        $post = $this->model->findOrFail($id);
        return $post->delete();
    }

    public function createHistory($data)
    {
        return BlogPostHistory::create($data);
    }
}
