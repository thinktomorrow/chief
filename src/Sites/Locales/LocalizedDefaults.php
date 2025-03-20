<?php

namespace Thinktomorrow\Chief\Sites\Locales;

trait LocalizedDefaults
{
    public function getActiveLocale(): string
    {
        return $this->getActiveDynamicLocale();
    }

    public function getLocales(): array
    {
        return $this->getDynamicLocales();
    }

    public function getFallbackLocales(): array
    {
        return $this->getDynamicFallbackLocales();
    }
}
