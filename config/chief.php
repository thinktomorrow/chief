<?php

return [

    /**
     * When chief is in strict mode, it exposes potential errors and warnings in your application.
     * Non-critical errors like non found urls or views. When such an error occurs in strict mode,
     * your app will throw an exception. Strict mode is by default only enabled in development.
     */
    'strict' => env('APP_DEBUG', false),

    /**
     * Locales
     *
     * Chief is designed with localisation in mind. You can set up the different locales
     * that should be available in the admin and site.
     */
//    'locales' => [
//
//        /**
//         * Application locales
//         *
//         * The available application locales in which model values can be localized in.
//         * The translatable fields will be editable for each locale accordingly. Note
//         * that you can still override this for each model.
//         *
//         * The first locale in this list is considered the default required locale.
//         */
//        'admin' => [
//            'nl',
//        ],
//
//        /**
//         * Active sites.
//         * These are the locales (sites) that are allowed on the frontend.
//         *
//         * Default this value is null, which means that all sites are active.
//         * You can change this to an array of locales. e.g. ['nl', 'fr']
//         */
//        'site' => null,
//    ],

    /**
     * Sites
     *
     * Define the different sites that are available in your application. The key of each
     * site is the locale of the site. The value is an array with the following keys:
     *
     * - name        The name of the site used throughout the admin
     * - short_name  A short name used in admin select lists or tabs.
     * - url         The root url of the site for this locale
     * - active      Whether this site is active on the frontend or not
     *
     * The first site in the list is considered the default site.
     */
    'sites' => [
        'nl' => [
            'name' => 'Site (be)',
            'short_name' => 'be',
            'url' => env('CHIEF_SITES_URL_DEFAULT', env('APP_URL', 'http://localhost')),
            'active' => true,

            // IDEA: app and fallback locale,

            'default_locale' => 'nl',
            // which locale to use for squanto, translations and such: app()->setLocale()
            //// Usually this is the same as the key locale but it can happen that the app locale is different.
            ///  This means that for sites with the same app_locale, only one translation field is presented. Then there is no option to
            ///  set a different translation for this locale.  If you like to do this in some occasions, you can click 'add different translation for 'be' and
            ///  this adds the be translation tab for this specific field. Referencing example: anzapro.nl > anzapro.be
            ///
            /// If app_locale is already a locale of a site, then this site is not immediately available in the translations and such. Only on demand.

            // ISO 639-1 locale format: language[-region]
            // e.g. nl, en, en-US, nl-NL, fr-BE
            // region is based on ISO 3166-1 alpha-2.
            // Note: language is required, region is optional. Important is that the language is always set first.
            'iso_code' => 'nl-BE',
        ],
        'fr' => [
            'name' => 'Site (fr)',
            'short_name' => 'fr',
            'url' => env('CHIEF_SITES_URL_FR', env('APP_URL', 'http://localhost')),
            'active' => false,
        ],
    ],

    /**
     * Chief admin locale
     *
     * Determines the locale in which the admin sees the pages and content.
     * This basically sets the app.locale to this value on admin visits.
     *
     * If set to null, the current set default is used. But be aware that any locale
     * manipulations, such as done by the thinktomorrow/locale::localeRoutePrefix()
     * can influence this locale. So it's better to set a specific locale value.
     */
    'admin_locale' => 'nl',

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

    'assets' => [

        /*
         * The maximum file size of an asset in bytes.
         * Adding a larger file will result in an exception.
         */
        'max_file_size_in_bytes' => 1024 * 1024 * 40, // 40MB

        //        'chunk_size_in_bytes' => 1024 * 1024 * 6, // 10MB
    ],

    /**
     * The default view path for all fragment classes. You can override this
     * per fragment by setting the viewPath property on your Fragment class.
     * Relative to the resources/views directory. Use dotted syntax for
     * nested directories, e.g. 'fragments.nested'.
     */
    'fragment_viewpath' => 'fragments',

    /**
     *
     */
    'fragments_structure' => [
        'algemeen' => [
            // Fragment::class,
            // Fragment::class,
            // Fragment::class,
        ],
        'formulieren' => [
            // Fragment::class,
            // Fragment::class,
            // Fragment::class,
        ],
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

    /**
     * During development, you'll sometimes want to disable required fields so you can
     * manually test some input heavy form submissions. To easy this manual stuff,
     * this flag allows the developer to disable any field requirements.
     *
     * This flag is only active in the local environment.
     */
    'disable_field_required_validation' => env('CHIEF_DISABLE_FIELD_REQUIRED_VALIDATION', false),
];
