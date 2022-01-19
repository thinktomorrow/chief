<?php

namespace Thinktomorrow\Chief\Forms\Fields\Locale;

interface Localizable
{
    public function locales(?array $locales = null): static;

    public function getLocales(): array;

    public function hasLocales(): bool;

    public function getLocalizedFormKey(): LocalizedFormKey;
}
