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
     * All the locales where the field should be used in.
     * This are the locales in which the field should
     * be presented to the admin.
     */
    public function getLocales(): array;

    /**
     * Indicates whether this field is localized or not.
     */
    public function hasLocales(): bool;

    public function setLocales(array $locales): static;

    /**
     * Get the locale for current scope. This is used to render the field
     * in the locale where the admin is currently working in.
     */
    //    public function getScopedLocale(): ?string;
    //
    //    public function setScopedLocale(?string $locale): static;

    /**
     * Locales in which the field should be presented to the admin.
     * These are the active locales of the page / fragment.
     */
    //    public function getScopedLocales(): array;

    //    public function setScopedLocales(array $scopedLocales): static;
    //
    //    /**
    //     * The locales that are not scoped to the given context.
    //     * E.g. locales of shared field not used here but in other context.
    //     */
    //    public function getDormantLocales(): array;
    //
    //    public function setDormantLocales(array $dormantLocales): static;

    /**
     * Get the active fallback locale for the given locale.
     */
    public function getFallbackLocale(string $locale): ?string;

    /**
     * Check if the locale has its own value set. This is used to
     * present the field in the admin with the correct tabs.
     */
    public function hasOwnLocaleValue(string $locale): bool;

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
