<?php

namespace App\Http\Controllers;

use App\Models\PlatformAccount;
use App\Services\PlatformAccountService;
use App\Services\WordpressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WordpressController extends Controller
{
    protected PlatformAccountService $platform_account_service;
    protected WordpressService $wordpress_service;
    protected $uuid = '06018b97-db7d-4c8d-a9e6-e75f6053aa83';

    public function __construct(PlatformAccountService $platform_account_service, WordpressService $wordpress_service)
    {
        $this->platform_account_service = $platform_account_service;
        $this->wordpress_service = $wordpress_service;
    }

    public function checkPlatformAccount(Request $request)
    {
        $platform_account_id = 1;
        $platform_account = $this->platform_account_service->findByID($platform_account_id);
        if (!empty($platform_account)) {
            return response()->json([
                'success' => false,
                'message' => 'Already Exist.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => ''
        ]);
    }

    public function createPlatformAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wordpress_url' => 'required|url',
            'wordpress_username' => 'required',
            'wordpress_api_key' => 'required',
        ]);

        if ($validator->passes()) {
            $validated_data = $validator->validated();
            $uuid = $this->uuid;

            $platform_account = [
                'uuid' => $uuid,
                'platform_name' => PlatformAccount::PLATFORM_WORDPRESS,
                'username' => $validated_data['wordpress_username'],
                'api_key' => $validated_data['wordpress_api_key'],
                'url' => rtrim($validated_data['wordpress_url'], '\/\\'),
            ];

            if (!$this->checkAccessWordpress($platform_account['url'], $platform_account['username'], $platform_account['api_key'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'URL, USR, PWD may be not correct.'
                ]);
            }

            $response = $this->platform_account_service->store($platform_account);

            if (!$response) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cant store data into database.'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Added new records.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $validator->errors()]);
    }

    private function checkAccessWordpress($wp_url, $wp_username, $wp_application_pwd)
    {
        return $this->wordpress_service->checkAccessWordpress($wp_url, $wp_username, $wp_application_pwd);
    }

    public function publishArticle(Request $request)
    {
        $platform_account_id = 1;

        $platform_account = $this->platform_account_service->findByID($platform_account_id);
        if (empty($platform_account)) {
            abort(403, 'Unauthorized action.');
        }

        $faker = fake('ja_jp');
        $title = ucwords($faker->realTextBetween(10, 30));
        $content = $faker->realTextBetween(200, 400);

        $resp = $this->wordpress_service->publishArticle($platform_account, $title, $content);

        return response()->json($resp);
    }
}
