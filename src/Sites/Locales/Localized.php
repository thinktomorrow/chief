<?php

namespace Thinktomorrow\Chief\Sites\Locales;

interface Localized
{
    public function getActiveLocale(): string;

    public function getLocales(): array;

    public function getFallbackLocales(): array;
}
