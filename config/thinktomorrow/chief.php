<?php

return [

    /**
     * Contact email which will recieve all incoming communication
     * This contact will receive e.g. contactform submissions
     */
    'contact'   => [
        'email' => env('MAIL_ADMIN_EMAIL','info@thinktomorrow.be'),
        'name' => env('MAIL_ADMIN_NAME','Think Tomorrow')
    ],

    /**
     * Name of the project
     */
    'name' => 'Chief',

    /**
     * Client name
     */
    'client' => 'Think Tomorrow',

    /**
     * Here we define which models are allowed to be set as children or parents
     * After changing this value, make sure you flush the cached relations.
     * This has no effect on already created relations, only new ones.
     */
    'relations' => [

        'children' => [
            \Chief\Pages\Page::class,
        ],

        'parents' => [
            \Chief\Pages\Page::class,
        ],
    ],

];