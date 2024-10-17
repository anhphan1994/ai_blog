<?php

namespace App\Http\Controllers;

use Faker\Factory;
use Illuminate\Support\Facades\Http;

class WordpressController extends Controller
{
    public function test()
    {

//        die('12392139');

        $USERNAME = 'admin';
//        $PASSWORD = 'Si7n 5XBZ xZya mOAA WWRG IsoE';
        $PASSWORD = 'OJOgUGLmd4vzuxhHH2BU6760';
        $faker = Factory::create('ja_jp');

        // status: publish, future, draft, pending, and private
        $data = [
            'title' => ucwords($faker->realTextBetween(10, 30)),
            'status' => 'publish',
            'slug' => $faker->slug(),
            'content' => $faker->realTextBetween(200, 400)
//            'content' => $faker->paragraph()
        ];

        $authorization = base64_encode($USERNAME . ':' . $PASSWORD);
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $authorization,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->timeout(5) // Timeout sau 5 giây
        ->post('https://ai_wordpress.test/wp-json/wp/v2/posts', $data);

//        curl --user "admin:OJOgUGLmd4vzuxhHH2BU6760" https://ai_wordpress.test/wp-json/wp/v2/users?context=edit

//        echo '<pre>';


        if ($response->successful()) {
            $article =  $response->json();

            if(!$article){
                echo 'Không tìm thấy thông tin article sau khi publishing.'
            }

            echo "Bài viết đã được đăng thành công!";
            echo '<br>';
            var_dump($response->body());
        } else {
            var_dump($response->status());
            echo "Có lỗi xảy ra: " . $response->body();
        }

        exit;

//        $response = Http::post('https://example.com/api/data', [
//            'key' => 'value',
//            'another_key' => 'another_value',
//        ]);

//        if ($response->successful()) {
//            // Yêu cầu thành công
//            $body = $response->body();
//            var_dump($body);
//        } elseif ($response->failed()) {
//            // Yêu cầu thất bại
//            die('Yêu cầu thất bại');
//        } elseif ($response->clientError()) {
//            // Lỗi 4xx
//            die('Lỗi 4xx');
//        } elseif ($response->serverError()) {
//            // Lỗi 5xx
//            die('Lỗi 5xx');
//        }
    }
}
