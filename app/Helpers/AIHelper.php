<?php

namespace App\Helpers;

use App\Models\Admin;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Log;

class AIHelper
{
    public static function sendMessageToAI($prompt)
    {
        if(empty($prompt)){
            return 'No question provided.';
        }
        
        $endpoint = config('ai.endpoint');
        $apiKey = config('ai.api_key');
        $no_response_msg = config('ai.response.no_response');
        
        $response = Http::timeout(300)->post($endpoint, [
            'api_key' => [$apiKey],
            'chat_type' => ['chat'],
            'train_title' => [''],
            'question' => [$prompt],
        ]);

        // check if the response is not successful
        if ($response->status() === 400) {
            Log::error('AI response error: ' . json_encode($response->json()));
            return $no_response_msg; 
        }

        return $response->json()['response_chat'][0] ?? $no_response_msg;
    }
}
