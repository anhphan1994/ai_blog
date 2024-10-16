<?php

namespace App\Services;

use App\Repositories\Interfaces\BlogPostRepositoryInterface;

class BlogPostService
{
    protected $repository;

    public function __construct(BlogPostRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllPosts($params = [])
    {
        return $this->repository->getAll($params);
    }

    public function getPostById($id = null)
    {
        return $this->repository->getById($id);
    }

    public function createPost(array $data = [])
    {
        return $this->repository->create($data);
    }

    public function updatePost($id = null, array $data = [])
    {
        return $this->repository->update($id, $data);
    }

    public function deletePost($id = null)
    {
        return $this->repository->delete($id);
    }

    public function deleteMultiPosts($ids = [])
    {
        $result = [];
        foreach ($ids as $id) {
            $result[] = $this->deletePost($id);
        }
        return $result;
    }

    public function getAllStatus()
    {
        return $this->repository->getAllStatus();
    }

    public function getAllPeriod()
    {
        return $this->repository->getAllPeriod();
    }
}
