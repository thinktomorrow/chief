<?php

return [

    'default_suggester' => env('CHIEF_HIVE_DEFAULT_SUGGESTER', 'chatgpt'),
    'default_translator' => env('CHIEF_HIVE_DEFAULT_TRANSLATOR', 'chatgpt'),

    /**
     * Available AI / Translation Drivers
     */
    'drivers' => [
        'dummy' => \Thinktomorrow\Chief\Plugins\Hive\Drivers\DummyDriver::class,
        'chatgpt' => \Thinktomorrow\Chief\Plugins\Hive\Drivers\ChatGPT::class,
        'gemini' => \Thinktomorrow\Chief\Plugins\Hive\Drivers\Gemini::class,
        'deepl' => \Thinktomorrow\Chief\Plugins\Hive\Drivers\DeepL::class,
    ],

    'openai' => [
        'api_key' => env('CHIEF_HIVE_CHATGPT_API_KEY'),
        'model' => env('CHIEF_HIVE_CHATGPT_MODEL', 'gpt-4o'),
        'timeout' => 10,
        'retries' => 3,
    ],

    'gemini' => [
        'api_key' => env('CHIEF_HIVE_GEMINI_API_KEY'),
        'timeout' => 10,
        'retries' => 3,
    ],

    'deepl' => [
        'api_key' => env('CHIEF_HIVE_DEEPL_API_KEY'),
        'api_url' => env('CHIEF_HIVE_DEEPL_API_URL', 'https://api-free.deepl.com/v2/translate'),
    ],

    'temperature' => (float) env('CHIEF_HIVE_TEMPERATURE', 0.7),

    'max_tokens' => (int) env('CHIEF_HIVE_MAX_TOKENS', 2000),

    /** Default context prompt for better focused responses of AI commands  */
    'context' => [
        'default' => 'You are a helpful assistant.',
        'translate' => 'Translate the following text.',
        'suggest' => 'Suggest improvements for the following text.',
    ],
];
