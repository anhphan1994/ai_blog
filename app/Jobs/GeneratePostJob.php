<?php

namespace App\Jobs;

use App\Services\AIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class GeneratePostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $params;
    protected $post_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $post_id, array $params)
    {
        $this->params = $params;
        $this->post_id = $post_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AIService $service)
    {
        set_time_limit(0);
        Log::info("Job started to generate blog content: {$this->post_id}");
        
        $params = $this->params;

        $short_desc = $params['short_description'];
        $keywords = $params['keywords'];
        $style = $params['post_style'];
        $num_of_section = $params['section_number'];

        Log::info('Params: ', ['short_desc' => $short_desc, 'style' => $style, 'num_of_section' => $num_of_section, 'keywords' => $keywords]);
        $service->generateBlogContent($this->post_id, $short_desc, $keywords, $style, $num_of_section);
        Log::info("Job finished to generate blog content: {$this->post_id}");
    }
}
