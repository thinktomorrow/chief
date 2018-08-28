<?php

return [

    /**
     * Here you should set which page is considered to be the homepage, aka the default page found at the url root.
     * e.g. 'homepage_id' => 2,
     */
    'homepage_id' => null,

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
     * Define your menus here. By default there is a generic 'main' menu but you
     * are free to add different ones as well. e.g. footer-menu, sidebar,...
     */
    'menus' => [
        'main' => [
            'label' => 'Hoofdnavigatie',
            'view'  => 'front.menus.main'
        ]
    ],
    // 'pagesets' => [
    //     'singles'   => [
    //         'action'     => DummyPageSetRepository::class.'@all',
    //         'parameters' => [2],
    //         'label'      => 'algemene paginas'
    //     ],
    // ]
];
