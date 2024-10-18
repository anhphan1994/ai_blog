<?php

namespace App\Repositories\Interfaces;

interface PlatformAccountRepositoryInterface
{
    public function findBy($column, $value);
    public function findByID($id);
    public function findByUUID($uuid);
    public function create($data);
    public function getPlatformAccounts($user_id);
    public function getPlatformAccountById($id);
}
