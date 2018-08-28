<?php

use Thinktomorrow\Chief\Common\TranslatableFields\FieldType;

return [

    /**
     * Here you should set which page is considered to be the homepage, aka the default page found at the url root.
     * e.g. 'homepage_id' => 2,
     */
    'homepage_id' => [
            'value' => 1,
            'field' => [
                'type'    => FieldType::SELECT,
                'options' => [
                    1,2,3
                ],
                'selected'    => 1,
                'label'       => 'Startpagina',
                'description' => 'Kies hier de landingspagina van de website.'
            ]
    ],

    /**
     * Contact email which will receive all incoming communication
     * This contact will receive e.g. contact form submissions
     */
    'contact'     => [
        'email' => env('MAIL_ADMIN_EMAIL', 'info@thinktomorrow.be'),
        'name'  => env('MAIL_ADMIN_NAME', 'Think Tomorrow'),
    ],

    'system-mail' => [
        'value' => 'info@thinktomorrow.be',
        'field' => [
            'type'        => FieldType::INPUT,
            'label'       => 'Systeem E-mail',
            'description' => 'Dit e-mail adres wordt door het systeem gebruikt voor bv. auto-replies, wachtwoord-reset mails, ...',
        ],
    ],

    'system-mail-name' => [
        'value' => 'Think rrow',
        'field' => [
            'type'        => FieldType::INPUT,
            'label'       => 'Naam afzender',
            'description' => 'Dit e-mail adres wordt door het systeem gebruikt voor bv. auto-replies, wachtwoord-reset mails, ...',
        ],
    ],

    /**
     * SEO title and description defaults. These are used on each page
     * as the default if they're not overwritten for that page.
     */
    'seo-title' =>  [
        'value' => 'Default SEO title',
        'field' => [
            'type'        => FieldType::INPUT,
            'label'       => 'Site titel',
            'description' => 'Deze titel wordt gebruikt als SEO-titel.',
        ],
    ],
    'seo-description' => [
        'value' => 'Default SEO description',
        'field' => [
            'type'        => FieldType::TEXT,
            'label'       => 'Korte omschrijving',
            'description' => 'Deze omschrijving wordt gebruikt als SEO-omschrijving.',
        ],
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
    
];
