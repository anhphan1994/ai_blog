<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Render Image
     * @param $content
     * @return array|null
     */
    public function renderImage($content)
    {
        $endpoint = 'https://test.api.ai-pro.pro/image_genarate';
        $params = [
            "model_type" => [
                "dalle3"
            ],
            "prompt" => [
                $content
            ],
            "image_size" => [
                "1024x1024"
            ],
            "image_style" => [
                "natural"
            ]
        ];
        $results = Http::post($endpoint, $params);

        if($results->successful()) {
            $data = $results->json();
            $base64Image = $data['base64_image_data'][0] ?? '';

            if ($base64Image) {
                $base64Image = 'data:image/png;base64,'.$base64Image;
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                    $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
                    $imageData = base64_decode($imageData);
                    $extension = strtolower($type[1]); // jpg, png, gif, etc.
                    // Generate a unique filename
                    $fileName = uniqid() . '.' . $extension;

                    // Save the image using the Storage facade
                    Storage::disk('public')->put('images/' . $fileName, $imageData);

                    // Optionally return the file path or response
                    $filePath = Storage::url('images/' . $fileName);

                    return [
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'file_type' => $extension
                    ];
                }
            }

        }

        return null;
    }
}
