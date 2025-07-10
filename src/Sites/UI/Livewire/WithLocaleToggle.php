<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;

trait WithLocaleToggle
{
    public array $locales = [];

    public ?string $scopedLocale = null;

    protected function setLocalesOnOpen(array $values, iterable $components): void
    {
        if ($this->showsLocalesForAnyField($components)) {
            $this->locales = $values['locales'] ?? ($this->isAllowedToSelectSites() ? [] : ChiefSites::locales());
            $this->scopedLocale = $values['scopedLocale'] ?? ($this->locales[0] ?? null);
        }
    }

    /**
     * In the case that the locales are editable, like in the create model component,
     * we need to make sure that the scoped locale is in the list of allowed locales.
     */
    //    public function updatedLocales(): void
    //    {
    //        if (! in_array($this->activeLocale, $this->locales)) {
    //            $this->activeLocale = $this->locales[0] ?? null;
    //        }
    //    }

    //    public function onScopedToLocale(string $locale): void
    //    {
    //        $this->activeLocale = $locale;
    //    }

    public function isAllowedToSelectSites(): bool
    {
        if (! isset($this->modelClass)) {
            return false;
        }

        return (new \ReflectionClass($this->modelClass))->implementsInterface(HasAllowedSites::class) && (new $this->modelClass)->allowSiteSelection();
    }

    /**
     * Check if the locale toggle is needed for the given fields.
     */
    protected function showsLocalesForAnyField(iterable $components): bool
    {
        $fields = Fields::make($components)->all();

        // If the fragment allows fragments, we always show the locale toggle
        if (isset($this->fragment) && $this->fragment->allowsFragments) {
            return true;
        }

        foreach ($fields as $field) {
            if ($field->showsLocales()) {
                return true;
            }
        }

        return false;
    }
}
