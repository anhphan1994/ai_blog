<?php

namespace App\Services;

use App\Helpers\AIHelper;
use App\Prompts\BlogPrompt;
use App\Repositories\BlogPostRepository;
use Cache;
use Log;

class AIService
{
    public function generateBlogContent($short_desc, $keyword, $style, $num_of_section)
    {
       
        $first_prompt = BlogPrompt::generateFirstPrompt($short_desc, $keyword, $style, $num_of_section);

        if (Cache::has('first_response')) {
            $first_response = Cache::get('first_response');
        } else {
            $first_response = AIHelper::sendMessageToAI($first_prompt);
            Log::info('First Response: ' . $first_response);
            Cache::put('first_response', $first_response, now()->addHours(10));
        }

        $title = explode("\n", $first_response)[0];

        $fist_response_arr = explode("\n", $first_response);
        $outline_arr = array_slice($fist_response_arr, 1);

        $outline_list = "";
        foreach ($outline_arr as $section) {
            $outline_list .= $section . "\n";
        }

        $second_prompt = BlogPrompt::generateSecondPrompt($title, $short_desc, $keyword, $style, $num_of_section);

        if (Cache::has('second_response')) {
            $second_response = Cache::get('second_response');
        } else {
            $second_response = AIHelper::sendMessageToAI($second_prompt);
            Log::info('Second Response: ' . $second_response);
            Cache::put('second_response', $second_response, now()->addHours(10));
        }

        $output = "";
        $section_title = "";
        if (Cache::has('content')) {
            $content = Cache::get('content');
        }else{
            foreach ($outline_arr  as $index => $section) {
                if($index == count($outline_arr) - 1){
                    break;
                }

                $section_title =  $section_title ."\n".$section;

                $main_prompt = BlogPrompt::generateMainPrompt($title, $short_desc, $keyword, $outline_list, $section_title);

                $res =  AIHelper::sendMessageToAI($main_prompt);

                if(!empty($res) && $res != 'No response from AI.'){
                    $output =  $output ."\n\n\n".$res;
                }
                Log::info('Section Titles: ' . $section_title);
                Log::info('AI response: ' . $output);
                
            }
            $content = $second_response . $output;
            Cache::put('content', $content, now()->addHours(10));
        }

        return $content ?? 'No response from AI.';
    }

    public function generateBlogTitle($short_desc, $keyword, $style, $num_of_section){

        $title_prompt = BlogPrompt::generateTitlePrompt($short_desc, $keyword, $style, $num_of_section);

        if (Cache::has('title_response')) {
            $title_response = Cache::get('title_response');
        } else {
            $title_response = AIHelper::sendMessageToAI($title_prompt);
            Log::info('Title Response: ' . $title_response);
            Cache::put('title_response', $title_response, now()->addHours(10));
        }

        return $title_response ?? 'No response from AI.';
    }

    public function generateBlogOutline($short_desc, $keyword, $style, $num_of_section){
      
        $outline_prompt = BlogPrompt::generateOutlinePrompt($short_desc, $keyword, $style, $num_of_section);

        if (Cache::has('outline_response')) {
            $outline_response = Cache::get('outline_response');
        } else {
            $outline_response = AIHelper::sendMessageToAI($outline_prompt);
            Log::info('Outline Response: ' . $outline_response);
            Cache::put('outline_response', $outline_response, now()->addHours(10));
        }

        return $outline_response ?? 'No response from AI.';
    }
}
