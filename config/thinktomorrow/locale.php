<?php

return [

    /**
     * Available locales for the application
     *
     * These should reflect the locale folders
     * inside the /resources/lang directory
     */
    'available_locales'     => ['nl','en'],

    /**
     * Hidden locale
     *
     * Provide the default locale for non-localized url endpoints. This displays the content
     * in this given locale without the presence of a locale in the url. e.g. example.com
     * gives the nl content instead of example.com/nl. Null means this feature is off
     *
     */
    'hidden_locale'       => 'nl',

    /**
     * Fallback locale
     *
     * Use this locale when no locale has been set.
     * Note that when the setting 'hidden locale' is enabled
     * this hidden locale will always be used as a fallback.
     *
     * If null the default Laravel fallback locale will be used.
     */
    'fallback_locale'       => null,

    /**
     * Route uri placeholder
     *
     * When this parameter key is passed, it will inject a
     * custom locale to the LocaleUrl::route() function
     * e.g. LocaleUrl::route('pages.home',['locale_slug' => 'en']);
     */
    'placeholder'    => 'locale_slug',

];