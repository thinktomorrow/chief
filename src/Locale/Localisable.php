<?php

namespace Thinktomorrow\Chief\Locale;

interface Localisable
{
    public function getLocales(): array;

    public function setLocales(array $locales): void;
}
