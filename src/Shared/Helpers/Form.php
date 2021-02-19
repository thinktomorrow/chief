<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Helpers;

class Form
{
    /**
     * Handle the translations form entries with a given callback
     *
     * @param array $translations
     * @param \Closure $callback
     */
    public static function foreachTrans(array $translations, \Closure $callback)
    {
        foreach ($translations as $locale => $trans) {
            foreach ($trans as $key => $value) {
                call_user_func($callback, $locale, $key, $value);
            }
        }
    }
}
