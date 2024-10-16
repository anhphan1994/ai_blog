<?php

namespace App\Http\Controllers;

use App\Helpers\AIHelper;
use App\Services\AIService;
use Cache;
use Illuminate\Http\Request;
use Log;

class AIController extends Controller
{
    protected $service;

    public function __construct(AIService $service)
    {
        $this->service = $service;
    }

    public function generateBlogContent(Request $request)
    {
        // set_time_limit(0);
        // $time_start = microtime(true);
        // $short_desc_test = "APEXの競技シーンとヴァロラントの競技シーンの比較";
        // $keyword_test = "Laz選手とImperial hal選手";
        // $style_test = "同じプロゲーマーとしての目線";
        // $num_of_section_test = "6";

        // $short_desc = $request->short_desc ?? $short_desc_test;
        // $keyword = $request->keyword ?? $keyword_test;
        // $style = $request->style ?? $style_test;
        // $num_of_section = $request->num_of_section ?? $num_of_section_test;

        // if (empty($short_desc) || empty($keyword) || empty($style) || empty($num_of_section)) {
        //     return response()->json(data: ['error' => 'Please provide all the required fields']);
        // }

        // $content = $this->service->generateBlogContent($short_desc, $keyword, $style, $num_of_section);
        // $time_end = microtime(true);

        // dd($content, $time_end - $time_start);
        // return response()->json(data: ['content' => $content, 'time' => $time_end - $time_start]);
    }

    public function generateBlogTitle(Request $request)
    {
        set_time_limit(0);

        $short_desc_test = "APEXの競技シーンとヴァロラントの競技シーンの比較";
        $keyword_test = "Laz選手とImperial hal選手";
        $style_test = "同じプロゲーマーとしての目線";
        $num_of_section_test = "7";

        $short_desc = $request->short_desc ?? $short_desc_test;
        $keyword = $request->keyword ?? $keyword_test;
        $style = $request->style ?? $style_test;
        $num_of_section = $request->num_of_section ?? $num_of_section_test;

        if (empty($short_desc) || empty($keyword) || empty($style) || empty($num_of_section)) {
            return response()->json(data: ['error' => 'Please provide all the required fields']);
        }


       $title = $this->service->generateBlogTitle($short_desc, $keyword, $style, $num_of_section);

        return response()->json(data: ['title' => $title]);
    }

    public function generateBlogOutline(Request $request)
    {
        set_time_limit(0);

        $short_desc_test = "APEXの競技シーンとヴァロラントの競技シーンの比較";
        $keyword_test = "Laz選手とImperial hal選手";
        $style_test = "同じプロゲーマーとしての目線";
        $num_of_section_test = "7";

        $short_desc = $request->short_desc ?? $short_desc_test;
        $keyword = $request->keyword ?? $keyword_test;
        $style = $request->style ?? $style_test;
        $num_of_section = $request->num_of_section ?? $num_of_section_test;

        if (empty($short_desc) || empty($keyword) || empty($style) || empty($num_of_section)) {
            return response()->json(data: ['error' => 'Please provide all the required fields']);
        }

        $outline = $this->service->generateBlogOutline($short_desc, $keyword, $style, $num_of_section);

        return response()->json(data: ['outline' => $outline]);
    }
}
