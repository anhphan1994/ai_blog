<?php

namespace App\Services;

use App\Repositories\PlatformAccountRepository;
use Illuminate\Support\Facades\DB;

class PlatformAccountService
{
    protected $platform_account_repository;

    public function __construct(PlatformAccountRepository $platform_account_repository)
    {
        $this->platform_account_repository = $platform_account_repository;
    }

    public function findByUUID($uuid)
    {
        return $this->platform_account_repository->findByUUID($uuid);
    }

    public function store($params)
    {

//        DB::beginTransaction();
        try {
            $response = $this->platform_account_repository->create($params);
//            DB::commit();
            return $response;
        } catch (\Exception $e) {
//            var_dump($e->getMessage());
//            DB::rollBack();
//            logger()->error(sprintf("PlatformAccountService@store %s", $e->getMessage() . PHP_EOL . $e->getTraceAsString()));

            return false;
        }
    }
}
