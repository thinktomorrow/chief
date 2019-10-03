<?php

return [

    /**
     * Allowed locales to be managed
     * @var array
     */
    'locales' => ['nl','fr','en'],

    /**
     * Exclude following lang groups from import
     * Here you list all translations that should be maintained by the developer
     * @var array
     */
    'excluded_files' => ['auth','pagination','passwords','validation','app'],

    /**
     * Path where the cached language files should be stored
     * @var string
     */
    'cache_path' => __DIR__.'/../cached',

    /**
     * Path where the laravel language files are stored
     * @var string
     */
    'lang_path' => __DIR__.'/../lang',

    /**
     * In case the translation key cannot be translated, this option
     * allows to display null instead of the key itself. This differs
     * from native Laravel behaviour where always the key is returned.
     */
    'key_as_default' => true,

    /**
     * Squanto utilises the htmlPurifier class to cleanup any submitted html.
     * You can here set the temp directory required for this package.
     */
    'htmlPurifierCache' =>  __DIR__.'/../htmlPurifierCache',

];
