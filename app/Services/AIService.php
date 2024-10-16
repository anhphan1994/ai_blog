<?php

namespace App\Services;

use App\Helpers\AIHelper;
use App\Models\BlogPost;
use App\Prompts\BlogPrompt;
use App\Repositories\Interfaces\BlogPostRepositoryInterface;
use Log;

class AIService
{
    protected $blogPostRepository;

    public function __construct(BlogPostRepositoryInterface $blogPostRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
    }

    public function generateBlogContent($post_id, $short_desc, $keyword, $style, $num_of_section)
    {
        
        $get_outline_prompt = BlogPrompt::generateFirstPrompt($short_desc, $keyword, $style, $num_of_section);
        $outline_res = AIHelper::sendMessageToAI($get_outline_prompt);
        Log::info(message: 'Outline Response: ' . $outline_res);
    
        $title = explode("\n", $outline_res)[0];

        if(!empty($title)){
            $this->blogPostRepository->update($post_id, ['title' => $title]);
        }
        
        $fist_response_arr = explode("\n", $outline_res);
        $res_arr = array_slice($fist_response_arr, 1);

        $outline_list = "";
        $outline_arr = [];
        foreach ($res_arr as $section) {
            if (empty($section)) {
                continue;
            }
            $outline_list .= $section . "\n";
            $outline_arr[] = $section;
        }
        
        if(!empty($outline_list)){
            $this->blogPostRepository->update($post_id, ['outline' => $outline_list]);
        }

        $first_section_prompt = BlogPrompt::generateSecondPrompt($title, $short_desc, $keyword, $style, $num_of_section);
        $first_section_res = AIHelper::sendMessageToAI($first_section_prompt);
        Log::info('First Section Response: ' . $first_section_res);
 
        $blog_content = $first_section_res;
        foreach ($outline_arr  as $index => $section) {
            Log::info("Blog content:\\n".$blog_content);
            if($index == count($outline_arr) - 1){
                break;
            }
            $old_content = $index == 0 ? $first_section_res : $old_content;

            $main_prompt = BlogPrompt::generateMainPrompt($title, $short_desc, $keyword, $outline_list, $old_content);
            $res =  AIHelper::sendMessageToAI($main_prompt);
            
            if(!empty($res) && $res != 'No response from AI.'){
                $old_content =  $old_content ."\n\n\n".$res;
                $blog_content = $blog_content ."\n\n\n".$res;
            }
        }

        if(!empty($blog_content)){
            $this->blogPostRepository->update($post_id, ['content' => $blog_content, 'status' => BlogPost::STATUS_GENERATED]);
        }
        
        Log::info('Blog content generated');
    }

    public function generateBlogTitle($short_desc, $keyword, $style, $num_of_section){

        $title_prompt = BlogPrompt::generateTitlePrompt($short_desc, $keyword, $style, $num_of_section);
       
        $title_response = AIHelper::sendMessageToAI($title_prompt);
        Log::info('Title Response: ' . $title_response);

        return $title_response ?? 'No response from AI.';
    }

    public function generateBlogOutline($short_desc, $keyword, $style, $num_of_section){
      
        $outline_prompt = BlogPrompt::generateOutlinePrompt($short_desc, $keyword, $style, $num_of_section);
        
        $outline_response = AIHelper::sendMessageToAI($outline_prompt);
        Log::info('Outline Response: ' . $outline_response);
            
        return $outline_response ?? 'No response from AI.';
    }
}
