<?php

namespace Thinktomorrow\Chief\Sites\Locales;

interface Localized
{
    public function setLocale(string $locale): void;

    public function setFallbackLocales(array $fallbackLocales): void;

    public function getLocale(): string;

    public function getFallbackLocales(): array;
}
