<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ページネーション設定
        Paginator::useBootstrap();

        // メール送信レートリミット設定
        RateLimiter::for('mailing', function ($job) {
            return Limit::perMinute(180); // 1分あたり180回
        });

        // キュー失敗時の処理
        Queue::failing(function (JobFailed $event) {
            Log::error('ジョブ実行失敗', [
                'exception' => $event->exception,
                'connection' => $event->connectionName,
                'job' => $event->job
            ]);
        });
    }
}
