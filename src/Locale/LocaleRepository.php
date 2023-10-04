<?php

namespace Thinktomorrow\Chief\Locale;

interface LocaleRepository
{
    public function saveLocales(Localisable $model, array $locales): void;
}
