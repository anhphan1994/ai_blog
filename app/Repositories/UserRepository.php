<?php

namespace App\Repositories;

use App\Models\BlogPost;
use App\Models\User;
use App\Repositories\Interfaces\BlogPostRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function createUser($data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->model->create($data);
    }
}
