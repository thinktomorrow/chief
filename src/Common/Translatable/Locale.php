<?php

namespace Chief\Common\Translatable;

class Locale
{
    public static function all()
    {
        return config('translatable.locales');
    }

    public static function getDefault()
    {
        return config('app.locale');
    }

    public static function getForSelect()
    {
        $locales = self::all();

        // full word representations
        $names = [
            'nl'    => 'Dutch',
            'fr'    => 'French',
            'en'    => 'English',
        ];

        return collect($names)->flip()->filter(function($locale) use($locales){
            return false !== array_search($locale,$locales);
        })->flip()->toArray();
    }
}