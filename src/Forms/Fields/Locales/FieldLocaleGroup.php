<?php

namespace Thinktomorrow\Chief\Forms\Fields\Locales;

class FieldLocaleGroup {
    public function __construct(public string $fallbackLocale, public array $locales){}

    public function add(string $locale): void
    {
        if(!in_array($locale, $this->locales)) {
            $this->locales[] = $locale;
        }
    }
}
