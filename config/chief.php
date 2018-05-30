<?php

return [

    /**
     * Contact email which will receive all incoming communication
     * This contact will receive e.g. contact form submissions
     */
    'contact'     => [
        'email' => env('MAIL_ADMIN_EMAIL', 'info@thinktomorrow.be'),
        'name'  => env('MAIL_ADMIN_NAME', 'Think Tomorrow'),
    ],

    /**
     * Name of the project.
     *
     * This is used in a couple of places such as the mail footer.
     */
    'name'        => 'Chief',

    /**
     * Client name
     */
    'client'      => 'Think Tomorrow',

    /**
     * Domain settings.
     *
     * Here you should set your primary location for your models
     * This is used in a couple of places such as the generator tools. Make
     * sure to set in your composer.json as a PSR-4 autoloaded namespace.
     */
    'domain'      => [
        'namespace' => 'Chief\\',
        'path'      => 'src/',
    ],

    /**
     * Here we define which models are allowed to be set as children or parents
     * After changing this value, make sure you flush the cached relations.
     * This has no effect on already created relations, only new ones.
     */
    'relations'   => [

        'children' => [
            \Thinktomorrow\Chief\Pages\Page::class,
        ],

        'parents' => [
            \Thinktomorrow\Chief\Pages\Page::class,
        ],
    ],

    /**
     * Here you should provide the mapping of page and component collections. This
     * is required for the class mapping from database to their respective classes.
     */
    'collections' => [
        'statics' => \Thinktomorrow\Chief\Pages\Page::class,
    ],

];
