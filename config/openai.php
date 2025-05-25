<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure the settings for interacting with the OpenAI API.
    |
    */

    'api_key' => env('OPENAI_API_KEY', ''),

    'organization' => env('OPENAI_ORGANIZATION_ID', ''),

    'request_timeout' => env('OPENAI_REQUEST_TIMEOUT', 30),

    'stream_timeout' => env('OPENAI_STREAM_TIMEOUT', 600),

    /*
    |--------------------------------------------------------------------------
    | Default Models
    |--------------------------------------------------------------------------
    |
    | This value is the default model to be used when the "model" option is not
    | explicitly provided to the facade or helper functions.
    |
    */
    'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),

    'chat' => [
        'model' => env('OPENAI_CHAT_MODEL', 'gpt-3.5-turbo'),
        'message_history' => 5,
    ],

    'system_message' => "Bạn là trợ lý AI thông minh của Medic Hospital, được thiết kế để cung cấp thông tin y tế và hỗ trợ bệnh nhân. Bạn cũng có thể trả lời các câu hỏi tổng quát khác ngoài lĩnh vực y tế. Hãy trả lời ngắn gọn, rõ ràng và hữu ích.",

    'embeddings' => [
        'model' => env('OPENAI_EMBEDDINGS_MODEL', 'text-embedding-ada-002'),
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Options
    |--------------------------------------------------------------------------
    |
    | Options for the HTTP client, such as timeout and retry.
    |
    */
    'http_options' => [
        'timeout' => env('OPENAI_HTTP_TIMEOUT', 30),
        'retry' => [
            'max_retries' => env('OPENAI_HTTP_MAX_RETRIES', 3),
            'retry_delay' => env('OPENAI_HTTP_RETRY_DELAY', 1000),
        ],
    ],

];
