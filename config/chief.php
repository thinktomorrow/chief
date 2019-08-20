<?php

return [

    /**
     * When chief is in strict mode, it exposes potential errors and warnings in your application.
     * Non-critical errors like non found urls or views. When such an error occurs in strict mode,
     * your app will throw an exception. Strict mode is by default only enabled in development.
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

    'route' => [
        /**
         * By default Chief will add the pages.show routing to your app. Since this is a catch-all route, it will be loaded last.
         * If this conflicts with your project, set the autoload value to false. In this case you are responsible for handling the routing.
         * Use the following route snippet as a starting point:
         *
         *      Route::get('{slug?}', function($slug = '/'){
         *          return \Thinktomorrow\Chief\Urls\ChiefResponse::fromSlug($slug);
         *      })->name('pages.show')->where('slug', '(.*)?');
         *
         */
        'autoload' => true,

        /**
         * Route name for the route that chief uses to listen and interact with
         * a page request. It is set to `pages.show` but if this conflicts
         * with your project naming conventions, you can change it here.
         */
        'name' => 'pages.show',
    ],

    /**
     * The Viewable::viewPath() method gives the view path for that specific model.
     * By default, we provide some sensible defaults for pages, modules and sets.
     * Here you define the relative base view path for these resp. models.
     */
    'base-view-paths' => [
        'pages'   => 'pages',
        'modules' => 'modules',
        'sets'    => 'sets',
    ],

    /**
     * Here we define which models are available as children.
     *
     * This reflects itself in the select options of the page builder. Make note that this has no effect on already
     * created relations, only new ones. After changing this value, make sure you flush the cached relations.
     */
    'relations'   => [
        'children' => [
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
     * Select the editor for the html fields. This is used for the html fields
     * in the forms as well as in the pagebuilder. Available options are:
     * 'quill' and 'redactor'. By default the free quill editor is set.
     */
    'editor' => 'quill',
];
