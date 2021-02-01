<?php

return [
    'locales' => ['nl' , 'en', 'fr'],
    'excluded_files' => ['auth','pagination','passwords','app', 'validation', 'routes'],
    'key_as_default' => true,
    'use_default_routes' => false,
    'lang_path' => __DIR__.'/../lang',
    'cache_path' => __DIR__.'/../cached',
    'metadata_path' => __DIR__.'/../metadata',

];
