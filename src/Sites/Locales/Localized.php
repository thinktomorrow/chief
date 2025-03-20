<?php

namespace Thinktomorrow\Chief\Sites\Locales;

interface Localized
{
    public function setActiveLocale(string $locale): void;

    public function getActiveLocale(): string;

    public function setLocales(array $locales): void;

    public function getLocales(): array;

    public function setFallbackLocales(array $fallbackLocales): void;

    public function getFallbackLocales(): array;
}
