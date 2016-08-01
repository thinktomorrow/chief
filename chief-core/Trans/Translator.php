<?php

namespace Chief\Trans;

use Chief\Trans\Domain\Trans;
use Illuminate\Translation\Translator as LaravelTranslator;

class Translator extends LaravelTranslator
{
    /**
     * Get the translation for the given key and follow this priority chain:
     *
     * 1. Get from our cached translations
     * 2. Get from database
     * 3. Get from the /resources/lang
     *
     * @param  string $key
     * @param  array $replace
     * @param  string $locale
     * @param bool $fallback
     * @return string
     */
    public function get($key, array $replace = array(), $locale = null, $fallback = true)
    {
        $locale = $locale ?: app()->getLocale();

        if($result = $this->getFromCache($key, $replace, $locale, $fallback)) return $result;

        if(!is_null($result = Trans::translateByKey($key, $replace, $locale, $fallback))) return $result;

        return parent::get($key, $replace, $locale, $fallback);
    }

    private function getFromCache($key, array $replace = array(), $locale = null, $fallback = true)
    {
        $namespacedkey = $key;

        if(false === strpos($namespacedkey,'tt::')) $namespacedkey = 'tt::'.$namespacedkey;

        $result = parent::get($namespacedkey, $replace, $locale, false);

        if($result !== $namespacedkey) return $result;

        return null;
    }
}