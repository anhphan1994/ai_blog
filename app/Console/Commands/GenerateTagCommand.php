<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\User;
use App\Services\APIService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class GenerateTagCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:tag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tags';

    protected $api_service;

    /**
     * @param APIService $tagService
     */
    public function __construct(APIService $api_service)
    {
        parent::__construct();
        $this->api_service = $api_service;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = now();
        logger()->info('Generate tags start at ' . $now->copy()->format('Y-m-d H:m:s'));
        User::query()->chunk(100, function ($users) use ($now) {
            foreach ($users as $user) {
                try {
                    $writer_article = $user->username.' '.$user->id;
                    Cache::remember($writer_article, $now->copy()->addHours(config('constant.cache_remember_hour')), function () use ($writer_article) {
                        return $this->api_service->getTags($writer_article);
                    });
                } catch (\Exception $ex) {
                    logger()->error('GenerateTagCommand@handle: '.$ex->getTraceAsString());
                }
            }
        });
        logger()->info('Generate tags end at ' . $now->copy()->format('Y-m-d H:m:s'));
        return Command::SUCCESS;
    }
}
