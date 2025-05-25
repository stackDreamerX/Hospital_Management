<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Cấu hình cho OpenAI API
        try {
            $apiKey = config('openai.api_key');

            // Kiểm tra và log loại API key (cho mục đích debug)
            if (str_starts_with($apiKey, 'sk-proj-')) {
                Log::info('Đang sử dụng OpenAI API key dạng project');
            } else {
                Log::info('Đang sử dụng OpenAI API key tiêu chuẩn');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi cấu hình OpenAI: ' . $e->getMessage());
        }
    }
}
