<?php

namespace Thinktomorrow\Chief\Locale;

trait LocalisableDefault
{
    public function getLocales(): array
    {
        return $this->locales;
    }

    public function setLocales(array $locales): void
    {
        $this->locales = $locales;
    }

    protected function initializeLocalisableDefault()
    {
        $this->mergeCasts(['locales' => 'array']);
    }
}
