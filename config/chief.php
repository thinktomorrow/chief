<?php

return [

    /**
     * When chief is in strict mode, it exposes potential errors and warnings in your application.
     * Non-critical errors like non found urls or views. When such an error occurs in strict mode,
     * your app will throw an exception. Strict mode is by default only enabled in development.
     */
    'strict' => env('APP_DEBUG', false),

    /**
     * Application locales
     *
     * The available application locales in which model values can be localized in.
     * The translatable fields will be editable for each locale accordingly. Note
     * that you can still override this for each model or individual field.
     *
     * The first locale in this list is considered the default required locale.
     */
    'locales' => [
        'nl',
    ],

    'route' => [
        /**
         * By default Chief will add the pages.show routing to your app. Since this is a catch-all route, it will be loaded last.
         * If this conflicts with your project, set the autoload value to false. In this case you are responsible for handling the routing.
         * Use the following route snippet as a starting point:
         *
         *      Route::get('{slug?}', function($slug = '/'){
         *          return \Thinktomorrow\Chief\Site\Urls\ChiefResponse::fromSlug($slug);
         *      })->name('pages.show')->where('slug', '(.*)?');
         *
         */
        'autoload' => env('CHIEF_ROUTE_AUTOLOAD', true),

        /**
         * Route name for the route that chief uses to listen and interact with
         * a page request. It is set to `pages.show` but if this conflicts
         * with your project naming conventions, you can change it here.
         */
        'name' => 'pages.show',

        /**
         * Here you can set the general prefix for all the chief admin routes.
         * This is set to a sensible default '/admin'.
         */
        'prefix' => 'admin',

        /**
         * The filepath where you can define your projects' chief admin routes. The chief
         * admin prefix and middleware will automatically be applied to these routes
         */
        'admin-filepath' => null,
    ],

    /**
     * Widgets that will be shown on the dashboard
     */
    'widgets' => [

    ],

    /**
     * Define your menus here. By default there is a generic 'main' menu but you
     * are free to add different ones as well. e.g. footer-menu, sidebar,...
     */
    'menus' => [
        'main' => [
            'label' => 'Hoofdnavigatie',
        ],
    ],

    /**
     * Snippet parsing is no longer out of the box available. If you want this for your project,
     * you can overwrite the renderView or renderFragment methods and parse the content via the SnippetParser.
     * This way you'll get to parse the entire rendered html prior to returning it to the user.
     *
     * Define the directory where your html snippets reside. This can be a blade file or regular html.
     * The identifier of each snippet is taken from the filename so make sure to properly name
     * your files. We will load up all the snippets as available clips in e.g. the editor.
     * The given directory should be relative to the project root.
     */
    'loadSnippetsFrom' => [
        'resources/views/front/snippets',
    ],

    /**
     * Select the editor for the html fields. This is used for the html fields
     * in the forms as well as in the pagebuilder. Available options are:
     * 'quill' and 'redactor'. By default the free quill editor is set.
     */
    'editor' => 'redactor',

    /**
     * Here you can define the stack of checks used by the healthmonitor.
     */
    'healthMonitor' => [
        Thinktomorrow\Chief\Admin\HealthMonitor\Checks\HomepageSetCheck::class,
        Thinktomorrow\Chief\Admin\HealthMonitor\Checks\HomepageAccessibleCheck::class,
    ],

    /**
     * Preview mode
     *
     * With preview mode enabled, the admin can preview draft pages and modules on the live site.
     * Here you can tweak the default behavior:
     * -  null to disable preview mode altogether
     * - 'live' to use the live view as a default so the admin sees the site as any other visitor
     * - 'preview' to use the preview view as a default so the admin can see drafted elements as well.
     */
    'preview-mode' => 'live',
];
