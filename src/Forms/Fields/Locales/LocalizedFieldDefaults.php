<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Locales;

use Thinktomorrow\Chief\Forms\Fields\FieldName\FieldName;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasSiteLocales;

trait LocalizedFieldDefaults
{
    protected array $locales = [];

    protected array $scopedLocales = [];

    protected array $dormantLocales = [];

    public function locales(?array $locales = null): static
    {
        $this->locales = ($locales === null)
            ? ChiefSites::locales()
            : $locales;

        $this->whenModelIsSet(function ($model, $field) {

            // TODO: if model is fragment, set the locales of the fragment context
            // And set all locales to any locales that are set on the fragment context

            if ($model instanceof HasSiteLocales && empty($this->scopedLocales)) {
                $field->setScopedLocales(ChiefSites::verifiedLocales($model->getSiteLocales()));
            }
        });

        return $this;
    }

    public function getLocales(): array
    {
        return $this->locales;
    }

    public function hasLocales(): bool
    {
        return count($this->locales) > 0;
    }

    public function getScopedLocales(): array
    {
        if (empty($this->scopedLocales)) {
            return $this->getLocales();
        }

        // Use the sequence of locales as defined in the sites config
        return array_values(array_filter(ChiefSites::locales(), fn ($locale) => in_array($locale, $this->scopedLocales)));
    }

    public function setScopedLocales(array $scopedLocales): static
    {
        $this->scopedLocales = $scopedLocales;

        return $this;
    }

    public function getDormantLocales(): array
    {
        return $this->dormantLocales;
    }

    public function setDormantLocales(array $dormantLocales): static
    {
        $this->dormantLocales = $dormantLocales;

        return $this;
    }

    /**
     * Get the fallback locale for the given locale.
     * Prefer the scope if any
     */
    public function getFallbackLocale(string $locale): ?string
    {
        // Account for shared fragments which might have dormant locales
        $locales = array_merge($this->getScopedLocales(), $this->getDormantLocales());

        $fallbackLocales = array_filter(ChiefSites::fallbackLocales(), fn ($fallbackLocale) => in_array($fallbackLocale, $locales));

        return $fallbackLocales[$locale] ?? null;
    }

    /**
     * Check if the locale has its own value set. This is used to
     * present the field in the admin with the correct tabs.
     */
    public function hasOwnLocaleValue(string $locale): bool
    {
        return ! is_null($this->getValue($locale));
    }

    public function getLocalizedKeys(): array
    {
        return $this->getFieldName()
            ->dotted()
            ->matrix($this->getKey(), $this->getLocales());
    }

    public function getBracketedLocalizedNames(): array
    {
        return $this->getFieldName()
            ->bracketed()
            ->matrix($this->getName(), $this->getLocales());
    }

    public function getDottedLocalizedNames(): array
    {
        return $this->getFieldName()
            ->dotted()
            ->matrix($this->getName(), $this->getLocales());
    }

    /** @deprecated use getLocalizedFieldName() */
    public function getLocalizedFormKey(): FieldName
    {
        return $this->getFieldName();
    }

    /** @deprecated use getLocalizedFieldNameTemplate() */
    public function getLocalizedFormKeyTemplate(): string
    {
        return $this->getFieldNameTemplate();
    }

    /** @deprecated use setLocalizedFieldNameTemplate() */
    public function setLocalizedFormKeyTemplate(string $localizedFieldNameTemplate): static
    {
        return $this->setFieldNameTemplate($localizedFieldNameTemplate);
    }
}
