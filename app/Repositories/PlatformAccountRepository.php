<?php

namespace App\Repositories;


use App\Models\PlatformAccount;

class PlatformAccountRepository
{
    protected $platform_account;

    public function __construct(PlatformAccount $platform_account)
    {
        $this->platform_account = $platform_account;
    }

    private function findBy($column, $value)
    {
        return $this->platform_account->where($column, $value)->first();
    }

    public function findByID($id)
    {
        return $this->findBy('id', $id);
    }

    public function findByUUID($uuid)
    {
        return $this->findBy('uuid', $uuid);
    }

    public function create($data)
    {
        $data['uuid'] = fake()->uuid();
        return $this->platform_account->create($data);
    }
}
