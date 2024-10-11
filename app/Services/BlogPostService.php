<?php

namespace App\Services;

use App\Repositories\BlogPostRepository;

class BlogPostService
{
    protected $repository;

    public function __construct(BlogPostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllPosts()
    {
        return $this->repository->getAll();
    }

    public function getPostById($id)
    {
        return $this->repository->getById($id);
    }

    public function createPost(array $data)
    {
        return $this->repository->create($data);
    }

    public function updatePost($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deletePost($id)
    {
        return $this->repository->delete($id);
    }
}
