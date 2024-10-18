<?php

namespace App\Services;

use App\Models\PlatformAccount;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WordpressService
{
    public function testPublishArticle()
    {
        $faker = fake('ja_jp');
        $title = ucwords($faker->realTextBetween(10, 30));
        $content = $faker->realTextBetween(200, 400);
        $platform_account = PlatformAccount::find()->first();

        $this->publishArticle($platform_account, $title, $content);
    }

    public function checkAccessWordpress($wp_url, $wp_username, $wp_application_pwd)
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
            trackError('WordpressService@CheckAccessWordpress: Somethong went wrong', compact($wp_url, $wp_username, $wp_application_pwd));

            return false;
        }
    }

    public function publishArticle(PlatformAccount $platform_account, $title, $content, $slug = '')
    {
        if (empty($slug)) {
            $slug = Str::slug($title);
        }

        // status: publish, future, draft, pending, and private
        $article_payload = [
            'title' => $title,
            'status' => 'publish',
            'slug' => $slug,
            'content' => $content
        ];

        $wp_url = rtrim($platform_account->url, '\/\\') . '/wp-json/wp/v2/posts/';
        $authorization = base64_encode($platform_account->username . ':' . $platform_account->api_key);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $authorization,
                'Content-Type' => 'application/json',
            ])->post($wp_url, $article_payload);

            if ($response->successful()) {
                $article = $response->json();

                if (!$article) {
                    throw new Exception('Article not exists after publishing.');
                }

                return [
                    'success' => true,
                    'message' => 'Article published.'
                ];
            }

            throw new Exception('Something went wrong.');
        } catch (Exception $e) {
            trackError('WordpressService@publishArticle: ' . $e->getMessage(), $article_payload);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
