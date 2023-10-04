<?php

namespace Thinktomorrow\Chief\Forms\Fields\Common;

interface LocalisedField
{
    public function locales(?array $locales = null): static;

    public function getLocales(): array;

    public function hasLocales(): bool;

    public function setLocalizedFormKeyTemplate(string $localizedFormKeyTemplate): static;

    public function getLocalizedFormKeyTemplate(): string;

    public function getLocalizedFormKey(): LocalizedFormKey;
}
