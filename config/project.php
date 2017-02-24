<?php

return [

    /**
     * Contact email which will recieve all incoming communication
     * This contact will receive e.g. contactform submissions
     */
    'contact'   => ['email' => env('MAIL_ADMIN_EMAIL','info@thinktomorrow.be'), 'name' => env('MAIL_ADMIN_NAME','Think Tomorrow')],

    /**
     * Name of the project
     */
    'name' => 'Chief',

    /**
     * Client name
     */
    'client' => 'Think Tomorrow',

];