<?php

namespace Thinktomorrow\Chief\Sites\Locales;

trait LocalizedDefault
{
    public function setLocale(string $locale): void
    {
        $this->setActiveDynamicLocale($locale);
    }

    public function setFallbackLocales(array $fallbackLocales): void
    {
        $this->setDynamicFallbackLocales($fallbackLocales);
    }

    public function getLocale(): string
    {
        return $this->getActiveDynamicLocale();
    }

    public function getFallbackLocales(): array
    {
        return $this->getDynamicFallbackLocales();
    }
}
