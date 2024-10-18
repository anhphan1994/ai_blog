<?php

namespace App\Repositories;


use App\Models\PlatformAccount;
use App\Models\UserPlatformAccount;
use App\Repositories\Interfaces\PlatformAccountRepositoryInterface;
use Auth;

class PlatformAccountRepository implements PlatformAccountRepositoryInterface
{
    protected $platform_account;

    public function __construct(PlatformAccount $platform_account)
    {
        $this->platform_account = $platform_account;
    }

    public function findBy($column, $value)
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

        

        $platform_account = $this->platform_account->create($data);
        
        $existing = UserPlatformAccount::where('user_id', Auth::id())
            ->where('platform_account_id', $platform_account->id)
            ->first();

        if (!$existing) {
            UserPlatformAccount::create(['user_id' => Auth::id(), 'platform_account_id' => $platform_account->id]);
        }
        return $platform_account;
    }

    public function getPlatformAccounts($user_id)
    {
        $query = $this->platform_account->join('user_platform_accounts', 'platform_accounts.id', '=', 'user_platform_accounts.platform_account_id')
            ->where('user_platform_accounts.user_id', $user_id)
            ->select('platform_accounts.*')
            ->get();
        return $query;
    }

    public function getPlatformAccountById($id)
    {
        return $this->platform_account->find($id);
    }
}
