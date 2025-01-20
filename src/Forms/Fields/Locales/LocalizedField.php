<?php

namespace Thinktomorrow\Chief\Forms\Fields\Locales;

interface LocalizedField
{
    public function locales(?array $locales = null): static;

    // Get all locales the content should be available in.
    // These are the tabs shown for the fields in the admin.
    // array of locales and per locale the locales that fallback to this root locale
    // E.g. ['nl' => ['nl', 'en'], 'fr' => ['fr', 'fr-be']]

    // If locale value is not present, we assume this locale value should fallback to the fallback locale value.
    // If locale value is null or empty ('', null), we assume this value is explicitly set to empty and should not fallback.

    /**
     * Get all locales the content should be available in.
     */
    public function getFieldLocales(): ?FieldLocales;

    public function getLocales(): array;

    /**
     * Indicates whether this field is localized or not.
     */
    public function hasLocales(): bool;

    /**
     * Define a specific format for the locale key.
     * e.g. ':name.:locale' or 'trans.:locale.:name'
     */
    public function setLocalizedFormKeyTemplate(string $localizedFormKeyTemplate): static;

    //    public function getLocalizedFormKeyTemplate(): string;

    public function getLocalizedFormKey(): LocalizedFormKey;

    /**
     * Get all the localized keys for this field in bracketed format
     * e.g. ['name[nl]', 'name[en]']
     */
    public function getBracketedLocalizedNames(): array;

    /**
     * Get all the localized keys for this field in dotted format
     * e.g. ['name.nl', 'name.en']
     */
    public function getDottedLocalizedNames(): array;
}
