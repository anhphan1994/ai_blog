<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class APIService
{
    public function getTags($writer_article)
    {
        $endpoint = 'https://test.api.ai-pro.pro/trend_detect';
        $params = [
            'avatar_id' => ['FLkcTajAtJO1J3c'],
            'writer_article' => [$writer_article]
        ];
        $results = Http::post($endpoint, $params);

        if($results->successful()) {
            $data = $results->json();
            $tags = $data['response_chat'][0] ?? '';
            return $tags ? explode('、', $tags) : [];
        }

        return [];
    }
}
