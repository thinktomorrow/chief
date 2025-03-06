<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Locales;

use Thinktomorrow\Chief\Forms\Fields\FieldName\LocalizedFieldName;
use Thinktomorrow\Chief\Sites\BelongsToSites;
use Thinktomorrow\Chief\Sites\Locales\ChiefLocales;

trait LocalizedFieldDefaults
{
    protected array $locales = [];

    protected ?string $localizedFieldNameTemplate = null;

    public function locales(?array $locales = null): static
    {
        $this->locales = ($locales === null)
            ? ChiefLocales::locales()
            : $locales;

        $this->whenModelIsSet(function ($model, $field) use ($locales) {
            if ($model instanceof BelongsToSites && ($locales === null)) {
                $field->locales = ChiefLocales::localesBySites($model->getSiteIds());
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

    /**
     * Grouped locales by fallback logic. E.g. ['nl' => ['nl', 'en'], 'fr' => ['fr', 'fr-be']]
     * This is used to determine the tabs shown in the admin for the field.
     */
    public function getLocaleGroups(): array
    {
        $localesWithOwnValue = array_filter($this->locales, fn ($locale) => ! is_null($this->getValue($locale)));

        return ChiefLocales::localeGroups($this->locales, $localesWithOwnValue);
    }

    public function getLocalizedKeys(): array
    {
        return $this->getLocalizedFieldName()
            ->dotted()
            ->matrix($this->getKey(), $this->getLocales());
    }

    public function getBracketedLocalizedNames(): array
    {
        return $this->getLocalizedFieldName()
            ->bracketed()
            ->matrix($this->getName(), $this->getLocales());
    }

    public function getDottedLocalizedNames(): array
    {
        return $this->getLocalizedFieldName()
            ->dotted()
            ->matrix($this->getName(), $this->getLocales());
    }

    public function getLocalizedFieldName(): LocalizedFieldName
    {
        return LocalizedFieldName::make()
            ->bracketed()
            ->template(str_contains($this->name, ':locale') ? ':name' : $this->getLocalizedFieldNameTemplate());
    }

    public function getLocalizedFieldNameTemplate(): string
    {
        if (! $this->localizedFieldNameTemplate) {
            return LocalizedFieldName::getDefaultTemplate();
        }

        return $this->localizedFieldNameTemplate;
    }

    public function setLocalizedFieldNameTemplate(string $localizedFieldNameTemplate): static
    {
        $this->localizedFieldNameTemplate = $localizedFieldNameTemplate;

        return $this;
    }

    /** @deprecated use getLocalizedFieldName() */
    public function getLocalizedFormKey(): LocalizedFieldName
    {
        return $this->getLocalizedFieldName();
    }

    /** @deprecated use getLocalizedFieldNameTemplate() */
    public function getLocalizedFormKeyTemplate(): string
    {
        return $this->getLocalizedFieldNameTemplate();
    }

    /** @deprecated use setLocalizedFieldNameTemplate() */
    public function setLocalizedFormKeyTemplate(string $localizedFieldNameTemplate): static
    {
        return $this->setLocalizedFieldNameTemplate($localizedFieldNameTemplate);
    }
}
