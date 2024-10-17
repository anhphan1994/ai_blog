<?php

namespace App\Repositories;

use App\Models\BlogPost;
use App\Models\BlogPostHistory;
use App\Models\BlogPostParameter;
use App\Models\Media;
use App\Models\SeoSetting;
use App\Repositories\Interfaces\BlogPostRepositoryInterface;
use Illuminate\Support\Facades\Log;

class BlogPostRepository implements BlogPostRepositoryInterface
{
    protected $model;
    protected $perPage = 50;
    public function __construct(BlogPost $model)
    {
        $this->model = $model;
    }

    public function getAll($params, $count = false)
    {
        $query = $this->model->query();
        if (isset($params['platform_id']) && !empty($params['platform_id'])) {
            $query->join('post_platforms AS pp', 'pp.blog_post_id', '=', 'blog_posts.id');
            $query->where('pp.platform_account_id', $params['platform_id']);
        }
        if (isset($params['user_id']) && !empty($params['user_id'])) {
            $query->where('blog_posts.user_id', $params['user_id']);
        }
        if (isset($params['status']) && !empty($params['status']) && $params['status'] != 'all') {
            $query->where('blog_posts.status', $params['status']);
        }
        if (isset($params['search']) && !empty($params['search'])) {
            $query->where('blog_posts.title', 'like', '%' . $params['search'] . '%');
        }
        if (isset($params['period']) && !empty($params['period']) && $params['period'] != 'all') {
            $query->whereRaw('DATE_FORMAT(blog_posts.created_at, "%Y-%m") = ?', [$params['period']]);
        }
        $query->orderBy('blog_posts.id', 'desc');

        if ($count) {
            return $query->count();
        }
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

    public function getAllStatus()
    {
       return $this->model->select('status')->distinct()->get()->pluck('status');
    }

    public function getAllPeriod()
    {
        return $this->model->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as period')->distinct()->get()->pluck('period');
    }

    public function duplicate($id)
    {
        $post = $this->model->findOrFail($id);
        $data = $post->toArray();
        unset($data['id']);
        $data['title'] = $data['title'] . ' (copy)';
        $data['status'] = 'draft';
        $duplicatedPost = $this->model->create($data);

        $this->createHistory([
            'blog_post_id' => $duplicatedPost->id,
            'title' => $duplicatedPost->title,
            'content' => $duplicatedPost->content,
            'short_content' => $duplicatedPost->short_content,
            'status' => $duplicatedPost->status,
            'user_id' => $duplicatedPost->user_id,
        ]);

        return $duplicatedPost;
    }

    public function getBLogSEOSetting($id)
    {
        
        $query = $this->model->query();
        $query->leftJoin('seo_settings AS seo', 'seo.blog_post_id', '=', 'blog_posts.id');
        $query->select('seo.meta_title', 'seo.meta_description', 'seo.meta_keywords');
        $query->where('blog_posts.id', $id);
        return $query->first();
    }

    public function getPostStatus($id)
    {
        return $this->model->where('id', $id)->value('status');
    }

    public function createPostParams($data)
    {
        return BlogPostParameter::create($data);
    }

    public function getPostParams($id)
    {
        return BlogPostParameter::where('blog_post_id', $id)->first();
    }

    public function getPostContent($id)
    {
        return $this->model->where('id', $id)->value('content');
    }

    public function updateSEOSetting($id, $data)
    {
        $seo = SeoSetting::where('blog_post_id', $id)->first();
        if ($seo) {
            $seo->update($data);
        } else {
            $data['blog_post_id'] = $id;
            $seo = SeoSetting::create($data);
        }
        return $seo;
    }

    public function createMedia($data)
    {
        return Media::create($data);
    }

    public function updateTag($id, $data)
    {
        return 1;
    }
}
