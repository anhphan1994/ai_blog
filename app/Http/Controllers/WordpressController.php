<?php

namespace App\Http\Controllers;

use App\Services\PlatformAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class WordpressController extends Controller
{
    protected PlatformAccountService $platform_account_service;
    protected $uuid = '06018b97-db7d-4c8d-a9e6-e75f6053aa83';

    public function __construct(PlatformAccountService $platform_account_service)
    {
        $this->platform_account_service = $platform_account_service;
    }

    public function checkPlatformAccount(Request $request)
    {
        $user_uuid = $request->get('uuid', $this->uuid);
        if (empty($user_uuid)) {
            return response()->json([
                'success' => false,
                'message' => 'UUID Required.'
            ]);
        }
        $user_uuid = ''; // TEST
        $platform_account = $this->platform_account_service->findByUUID($user_uuid);
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
                'platform_name' => 'wordpress',
                'username' => $validated_data['wordpress_username'],
                'api_key' => $validated_data['wordpress_api_key'],
                'password' => rtrim($validated_data['wordpress_url'], '\/\\'),
            ];

            if (!$this->checkAccessWordpress($platform_account['password'], $platform_account['username'], $platform_account['api_key'])) {
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
        $url = rtrim($wp_url, '\/\\') . '/wp-json/wp/v2/plugins';

        $authorization = base64_encode($wp_username . ':' . $wp_application_pwd);
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $authorization,
            'Content-Type' => 'application/json',
        ])->get($url);

        if ($response->successful()) {
            $plugins = $response->json();

            return $plugins != null;
        } else {
            return false;
//            var_dump($response->status());
//            echo "Có lỗi xảy ra: " . $response->body();
        }
    }

    public function publishArticle(Request $request)
    {
        $user_uuid = $request->get('uuid', $this->uuid);
        if (empty($user_uuid)) {
            abort(403, 'Unauthorized action.');
        }

        $platform_account = $this->platform_account_service->findByUUID($user_uuid);
        if (empty($platform_account)) {
            abort(403, 'Unauthorized action.');
        }

        $faker = fake('ja_jp');

        // status: publish, future, draft, pending, and private
        $article_payload = [
            'title' => ucwords($faker->realTextBetween(10, 30)),
            'status' => 'publish',
            'slug' => $faker->slug(),
            'content' => $faker->realTextBetween(200, 400)
        ];

        $wp_url = rtrim($platform_account->password, '\/\\') . '/wp-json/wp/v2/posts/';
        $authorization = base64_encode($platform_account->username . ':' . $platform_account->api_key);
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $authorization,
            'Content-Type' => 'application/json',
        ])
            ->post($wp_url, $article_payload);

        if ($response->successful()) {
            $article = $response->json();

            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article not exists after publishing.'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Article published.'
            ]);
        } else {
            return $response->json([
                'success' => false,
                'message' => 'Something went wrong.'
            ]);
        }
    }
}
