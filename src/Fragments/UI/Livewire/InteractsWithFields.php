<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Fields\Field;

trait InteractsWithFields
{
    private function injectFormValues(iterable $components): void
    {
        foreach ($components as $component) {
            if (! $component instanceof Field && $component instanceof HasComponents) {
                $this->injectFormValues($component->getComponents());

                continue;
            }

            if ($component->hasLocales()) {
                foreach ($component->getDottedLocalizedNames() as $locale => $name) {
                    $this->injectFormValue($name, $component->getValue($locale));
                }
            } else {
                $this->injectFormValue($component->getName(), $component->getValue());
            }

            if ($component instanceof HasComponents) {
                $this->injectFormValues($component->getComponents());
            }
        }
    }

    private function injectFormValue(string $key, $value): void
    {
        Arr::set($this->form, $key, $value);
    }
}
