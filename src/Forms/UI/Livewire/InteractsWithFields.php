<?php

namespace Thinktomorrow\Chief\Forms\UI\Livewire;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Repeat;

trait InteractsWithFields
{
    private function injectFormValues(iterable $components): void
    {
        foreach ($components as $component) {

            if (! $component instanceof Field && $component instanceof HasComponents) {
                $this->injectFormValues($component->getComponents());

                continue;
            }

            $this->injectComponentFormValue($component);

            if ($component instanceof HasComponents && ! $component instanceof Repeat) {
                $this->injectFormValues($component->getComponents());
            }
        }
    }

    private function injectComponentFormValue($component): void
    {
        if ($component->hasLocales()) {
            foreach ($component->getDottedLocalizedNames() as $locale => $name) {

                $value = ($component instanceof Repeat)
                    ? ($component->getValue($locale) ?: [[]])
                    : $component->getValue($locale);

                $this->injectFormValue($name, $value);
            }
        } else {

            $value = ($component instanceof Repeat)
                ? ($component->getValue() ?: [[]])
                : $component->getValue();

            $this->injectFormValue($component->getName(), $value);
        }
    }

    private function injectFormValue(string $key, $value): void
    {
        Arr::set($this->form, $key, $value);
    }
}
