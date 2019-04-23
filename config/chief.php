<?php

use Thinktomorrow\Chief\Media\MediaType;

return [

    /**
     * When in development, we would like to have more errors and avoid silent fails which is
     * something that is appreciated in production but not in failure driven development. :)
     */
    'strict' => env('APP_DEBUG', false),

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
     * Definitions of the few route names that the chief backend uses to interact with the frontend.
     *
     * The `pages.show` serves as a catch all for displaying all Chief managed pages.
     * This catch-all route will point to a generic PagesController that directs the request
     * to the proper published page.
     *
     * `pages.home` makes the distinction with other pages in that it is accessible on the root url.
     */
    'routes' => [
        'pages-show' => 'pages.show',
        'pages-home' => 'pages.home',
    ],

    /**
     * Here you define the base view path for your pages, modules and sets. All module
     * views will be relative to this location. A sensible default has been set.
     * Note that is only is effect when not providing a custom viewPath.
     */
    'base-view-paths' => [
        'pages'   => 'pages',
        'modules' => 'modules',
        'sets'    => 'sets',
    ],

    /**
     * By default all models are available as children. Here we define which models are explicitly disallowed.
     *
     * This reflects itself in the select options of the page builder. Make note that this has no effect on already
     * created relations, only new ones. After changing this value, make sure you flush the cached relations.
     */
    'relations'   => [
        'blacklist' => [
            // \Thinktomorrow\Chief\Pages\Page::class,
        ],
    ],

    /**
     * Custom query sets.
     */
    'sets' => [
//         'singles'   => [
//             'action'     => DummyPageSetRepository::class.'@all',
//             'parameters' => [2],
//             'label'      => 'algemene paginas'
//         ],
    ],

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

    /**
     * Set of mediatypes used for each collection.
     * Default set of mediatypes that is available for every collection
     */
    'mediatypes' => [

        'default' => [
            (object) [
                'type' => MediaType::HERO,
                'limit' => 1,
            ],
            (object) [
                'type' => MediaType::THUMB,
                'limit' => 1,
            ]
        ],
    ],

    /**
     * Define the directory where your html snippets reside. This can be a blade file or regular html.
     * The identifier of each snippet is taken from the filename so make sure to properly name
     * your files. We will load up all the snippets as available clips in e.g. the editor.
     * The given directory should be relative to the project root.
     */
    'loadSnippetsFrom' => [
        'resources/views/front/snippets',
    ],

    /**
     * Enable snippet rendering by default.
     *
     * Pages and modules will parse any valid snippet placeholders found in a text or content
     * block and render it to the expected html. If set to false, you can always manually
     * manage this by calling the 'withSnippets()' method on a Page or Module object.
     */
    'withSnippets' => true,

    /**
     * Define specific setting fields.
     * By default a standard input field is used.
     */
    'settingFields' => [
        // TODO: callable can be removed when we set everything up in a service provider
        'homepage' => function () {
            return \Thinktomorrow\Chief\Settings\HomepageFieldGenerator::generate();
        },
        'contact.email' => \Thinktomorrow\Chief\Fields\Types\InputField::make('contact.email')
                        ->label('Webmaster email')
                        ->description('Het emailadres van de webmaster. Hierop ontvang je standaard alle contactnames.'),
        'contact.name' => \Thinktomorrow\Chief\Fields\Types\InputField::make('contact.name')
                        ->label('Webmaster naam')
                        ->description('Voor en achternaam van de webmaster.'),
        'client.app_name' => \Thinktomorrow\Chief\Fields\Types\InputField::make('client.app_name')
                        ->label('Site naam')
                        ->description('Naam van de applicatie. Dit wordt getoond in o.a. de mail communicatie.'),
        'client.name' => \Thinktomorrow\Chief\Fields\Types\InputField::make('client.name')
                        ->label('Organisatie')
                        ->description('Naam van uw bedrijf. Dit wordt getoond in o.a. de mail communicatie.'),
    ],
];
