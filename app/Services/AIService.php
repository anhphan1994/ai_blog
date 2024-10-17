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
        $is_error = false;
        $no_response_msg = config('ai.response.no_response');

        $title_outline = $this->generateTitleOutline($short_desc, $keyword, $style, $num_of_section);
        $title = $title_outline['title'];
        $outline = $title_outline['outline'];
        if(empty($title)){
            $is_error = true;
        }

        if(empty($outline)){
            $is_error = true;
        }

        $main_content = $this->generateMainContent($title, $short_desc, $keyword, $style, $num_of_section, $outline);
        

        if($is_error){
            Log::info(message: 'Error in generating blog content');
        }else{
            $this->blogPostRepository->update($post_id, 
                [
                    'title' => $title,
                    'outline' => $outline,
                    'content' => $main_content, 
                    'status' => BlogPost::STATUS_GENERATED
                    ]
            );
            Log::info('Blog content generated');
        }
    }

    public function generateTitleOutline($short_desc, $keyword, $style, $num_of_section){
        $get_outline_prompt = BlogPrompt::generateTitleOutlinePrompt($short_desc, $keyword, $style, $num_of_section);
        $outline_res = AIHelper::sendMessageToAI($get_outline_prompt);

        $title = $this->getTitle($outline_res);
        $outline = $this->getOutline($outline_res);

        return ['title' => $title, 'outline' => $outline];
    }

    public function getTitle($response){
        $title = explode("\n", $response)[0];
        if(empty($title) || $response == config('ai.response.no_response')){
            return '';
        }
        return $title;
    }

    public function getOutline($response){
        $fist_response_arr = explode("\n", $response);
        $res_arr = array_slice($fist_response_arr, 1);

        $outline_list = "";
        foreach ($res_arr as $section) {
            if (empty($section)) {
                continue;
            }
            $outline_list .= $section . "\n";
        }

        if(empty($outline_list) || $response == config('ai.response.no_response')){
            return '';
        }
        return $outline_list;
    }

    public function generateFirstSectionContent($title, $short_desc, $keyword, $style, $num_of_section){
        $prompt = BlogPrompt::generateFirstSectionContentPrompt($title, $short_desc, $keyword, $style, $num_of_section);
        $content = AIHelper::sendMessageToAI($prompt);

        if(empty($content) || $content == config('ai.response.no_response')){
            return '';
        }
        return $content;
    }

    public function generateMainContent($title, $short_desc, $keyword, $style, $num_of_section, $outline){

        $is_error = false;
        $outline_arr = explode("\n", $outline);
       
        $first_section_content = $this->generateFirstSectionContent($title, $short_desc, $keyword, $style, $num_of_section);
        
        if(empty($first_section_content)){
            $is_error = true;
        }

        $blog_content = $first_section_content;
        foreach ($outline_arr  as $index => $section) {
            Log::info("Blog content:\\n".$blog_content);
            if($index == count($outline_arr) - 1){
                break;
            }

            $old_content = $index == 0 ? $first_section_content : $old_content;

            $main_prompt = BlogPrompt::generateMainPrompt($title, $short_desc, $keyword, $outline, $old_content);
            $res =  AIHelper::sendMessageToAI($main_prompt);
            
            if(!empty($res) && $res != config('ai.response.no_response')){
                $old_content =  $old_content ."\n\n\n".$res;
                $blog_content = $blog_content ."\n\n\n".$res;
            }

            if(empty($res) || $res == config('ai.response.no_response')){
                $is_error = true;
                break;
            }
        }

        if($is_error){
            return '';
        }

        return $blog_content;
    }
}
