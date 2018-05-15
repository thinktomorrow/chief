<?php

return [

    /**
     * Contact email which will receive all incoming communication
     * This contact will receive e.g. contact form submissions
     */
    'contact'   => [
        'email' => env('MAIL_ADMIN_EMAIL','info@thinktomorrow.be'),
        'name' => env('MAIL_ADMIN_NAME','Think Tomorrow')
    ],

    /**
     * Name of the project.
     * This is also used in a couple of places such as the mail footer.
     */
    'name' => 'Chief',

    /**
     * Client name
     */
    'client' => 'Think Tomorrow',

];