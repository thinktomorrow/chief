<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;

trait WithLocaleToggle
{
    public array $locales = [];

    public ?string $scopedLocale = null;

    protected bool $shouldShowLocaleToggle = false;

    protected function initializeLocales(array $values, iterable $components): void
    {
        $this->determineIfLocalesShouldBeShown($components);

        if (! $this->shouldShowLocaleToggle) {
            return;
        }

        $this->locales = $values['locales'] ?? ($this->modelAllowsLocaleSelection() ? [] : ChiefSites::locales());
        $this->scopedLocale = $values['scopedLocale'] ?? ($this->locales[0] ?? null);
    }

    /**
     * Ensure the active locale remains in sync with the available locales.
     *
     * In the case that the locales are editable, like in the create model component,
     * we need to make sure that the scoped locale is in the list of allowed locales.
     */
    public function updatedLocales(): void
    {
        if (! in_array($this->scopedLocale, $this->locales)) {
            $this->scopedLocale = $this->locales[0] ?? null;
        }
    }

    public function modelAllowsLocaleSelection(): bool
    {
        if (! isset($this->modelClass)) {
            return false;
        }

        $class = new \ReflectionClass($this->modelClass);

        return $class->implementsInterface(HasAllowedSites::class)
            && (new $this->modelClass)->allowSiteSelection();
    }

    protected function shouldShowLocaleToggle(): bool
    {
        return $this->modelAllowsLocaleSelection() && $this->shouldShowLocaleToggle;
    }

    /**
     * Check if the locale toggle is needed for the given fields.
     */
    protected function determineIfLocalesShouldBeShown(iterable $components): void
    {
        $fields = Fields::makeWithoutFlatteningNestedFields($components)->all();

        $this->shouldShowLocaleToggle = false;

        // If the fragment allows fragments, we always show the locale toggle
        if (isset($this->fragment) && $this->fragment->allowsFragments) {
            $this->shouldShowLocaleToggle = true;

            return;
        }

        foreach ($fields as $field) {
            if ($field->showsLocales()) {
                $this->shouldShowLocaleToggle = true;

                return;
            }
        }
    }
}
