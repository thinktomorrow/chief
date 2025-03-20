<?php

namespace Thinktomorrow\Chief\Sites\Locales;

trait LocalizedDefaults
{
    public function setActiveLocale(string $locale): void
    {
        $this->setActiveDynamicLocale($locale);
    }

    public function getActiveLocale(): string
    {
        return $this->getActiveDynamicLocale();
    }

    public function setLocales(array $locales): void
    {
        $this->setDynamicLocales($locales);
    }

    public function getLocales(): array
    {
        return $this->getDynamicLocales();
    }

    public function setFallbackLocales(array $fallbackLocales): void
    {
        $this->setDynamicFallbackLocales($fallbackLocales);
    }

    public function getFallbackLocales(): array
    {
        return $this->getDynamicFallbackLocales();
    }
}
