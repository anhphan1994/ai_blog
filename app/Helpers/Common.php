<?php
use App\Services\WordpressService;

if (!function_exists('trackInfo')) {
    function trackInfo(string $message, array $context = []): void
    {
        Log::info($message, $context);
    }
}

if (!function_exists('trackError')) {
    function trackError(string $message, array $context = []): void
    {
        Log::error($message, $context);
    }
}

if (!function_exists('getStatusName')) {
    function getStatusName(string $status): string
    {
        $statusName = '';
        switch ($status) {
            case 'generated':
                $statusName = '記事生成中';
                break;
            case 'draft':
                $statusName = '下書き';
                break;
            case 'scheduled':
                $statusName = '予約投稿';
                break;
            case 'published':
                $statusName = '公開中';
                break;
            case 'deleted':
                $statusName = '削除';
                break;
            default:
                $statusName = '記事生成中';
                break;
        }
        return $statusName;
    }
}

if (!function_exists('getStatusClass')) {
    function getStatusClass(string $status): string
    {
        $statusClass = '';
        switch ($status) {
            case 'generated':
                $statusClass = '';
                break;
            case 'draft':
                $statusClass = 'st2';
                break;
            case 'scheduled':
                $statusClass = 'st3';
                break;
            case 'published':
                $statusClass = 'st4';
                break;
            case 'deleted':
                $statusClass = 'st5';
                break;
            default:
                $statusClass = '';
                break;
        }
        return $statusClass;
    }
}

//get platform account name by id
if (!function_exists('getPlatformAccountName')) {
    function getPlatformAccountName()
    {
        $wordpress_service = app(WordpressService::class);
        $platform_accounts = $wordpress_service->getPlatformAccounts(Auth::id());
        return $platform_accounts;
    }
}