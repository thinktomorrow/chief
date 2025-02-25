<?php

return [

    /**
     * When chief is in strict mode, it exposes potential errors and warnings in your application.
     * Non-critical errors like non found urls or views. When such an error occurs in strict mode,
     * your app will throw an exception. Strict mode is by default only enabled in development.
     */
    'strict' => env('APP_DEBUG', false),

    /**
     * Sites
     *
     * Define the different sites that are available in your application.
     *
     * The id of each site is the unique site identifier and also the handle in the db and views.
     * The locale / fallback_locale value will be set as app_locale and should also correspond
     * to the Laravel translation locale in resources/lang. e.g. nl, en, en-US, nl-NL, fr-BE
     *
     * Each site entry contains the following values:
     * - id                 The unique identifier of the site - used in the db and views. Don't alter this!
     * - locale             The locale used by this site
     * - fallback_locale    The locale to fall back to when a field value is not available in the locale
     *                      Great for when you just want a few values different from another locale. e.g. be -> nl, or en-us -> en-gb.
     *                      All translations use this locale by default and user can opt to occasionally add own locale values when needed.
     *                      This locale is used as a fallback when a field (or asset) value is not available in the locale.
     *                      Leave null if you don't want to use a fallback strategy.
     *
     * - name               The name of the site used throughout the admin
     * - short_name         A short name used in admin select lists or tabs.
     * - url                The base url of the site.
     *                      Frontend url of the site. Url indicates the base url of the site.
     *                      Note that this does not generate the routes but is only in the Chief admin for referencing
     *                      the correct site. Frontend routes are generated by e.g. the thinktomorrow/locale package.
     *
     * - active             Whether this site is active on the frontend or not
     * - primary            Whether this site is the primary site. Primary site is used as the default site in the admin.
     */
    'sites' => [
        [
            'id' => 'nl-be',
            'locale' => 'nl',
            'fallback_locale' => null,
            'name' => 'België (nl)',
            'short_name' => 'be',
            'url' => env('CHIEF_SITES_PRIMARY_URL', env('APP_URL', 'http://localhost')),
            'active' => true,
            'primary' => true,
        ],
        [
            'id' => 'fr-site',
            'locale' => 'fr',
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
     * view_path            The default view path for all fragment classes.
     *                      Relative to the resources/views directory. Use dotted syntax for
     *                      nested directories, e.g. 'fragments.nested'. You can override this
     *                      per fragment by setting the viewPath property on your Fragment class.
     *
     * admin_view_path      The default view path for all fragment admin views.
     *                      You can override this per fragment by setting the adminViewPath
     *                      property on your Fragment class.
     */
    'fragments' => [

        'view_path' => 'fragments',

        'admin_view_path' => 'back.fragments',
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
