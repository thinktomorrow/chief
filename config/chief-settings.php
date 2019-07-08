<?php

return [

    /**
     * Contact person (aka webmaster)
     *
     * The contact person receives all incoming communication e.g. contact form submissions
     * and is the sender address for all transaction mails such as password reset mails.
     */
    'contact' => [
        'email' => env('MAIL_ADMIN_EMAIL', 'info@thinktomorrow.be'),
        'name'  => env('MAIL_ADMIN_NAME', 'Think Tomorrow'),
    ],

    /**
     * Client details
     *
     * This is mainly a backend thing but it can occur in a
     * couple of frontend places such as the mail footer.
     */
    'client' => [
        'app_name' => 'Chief',
    ]
];
