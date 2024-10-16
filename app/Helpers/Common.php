<?php

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


//get status name by status
if (!function_exists('getStatusName')) {
    function getStatusName(string $status): string
    {
        $statusName = '';
        switch ($status) {
            case 'genereated':
                $statusName = '生成';
                break;
            case 'draft':
                $statusName = '下書き';
                break;
            case 'scheduled':
                $statusName = '予約';
                break;
            case 'published':
                $statusName = '公開';
            case 'deleted':
                $statusName = '削除';
                break;
            default:
                $statusName = '不明';
                break;
        }
        return $statusName;
    }
}