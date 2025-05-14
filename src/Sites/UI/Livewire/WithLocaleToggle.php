<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

use Thinktomorrow\Chief\Forms\App\Queries\Fields;

trait WithLocaleToggle
{
    public array $locales = [];

    public ?string $scopedLocale = null;

    protected function setLocaleToggleOnOpen(array $values, iterable $components): void
    {
        if ($this->areAnyFieldsLocalized($components)) {
            $this->locales = $values['locales'];
            $this->scopedLocale = $values['scopedLocale'];
        }
    }

    protected function areAnyFieldsLocalized(iterable $components): bool
    {
        $fields = Fields::make($components)->all();

        foreach ($fields as $field) {
            if ($field->hasLocales()) {
                return true;
            }
        }

        return false;
    }
}
