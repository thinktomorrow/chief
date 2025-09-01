<?php

namespace Thinktomorrow\Chief\Forms\UI\Livewire;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Fields\Checkbox;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\SelectList;

trait InteractsWithFields
{
    private function injectFormValues(iterable $components): void
    {
        foreach ($components as $component) {

            if (! $component instanceof Field) {
                if ($component instanceof HasComponents) {
                    $this->injectFormValues($component->getComponents());
                }

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
                $value = ($component instanceof Repeat && $this->isEmptyRepeatValue($component->getValue($locale)))
                    ? $this->composeEmptyRepeatValue($component, $locale)
                    : $component->getValue($locale);

                // TODO: find better way to set default empty value... maybe a method on the component itself?
                if (! $value && ($component instanceof Checkbox || $component instanceof SelectList)) {
                    $value = [];
                }

                $this->injectFormValue($name, $value);
            }
        } else {
            $value = ($component instanceof Repeat && $this->isEmptyRepeatValue($component->getValue()))
                ? $this->composeEmptyRepeatValue($component)
                : $component->getValue();

            // TODO: find better way to set default empty value... maybe a method on the component itself?
            if (! $value && ($component instanceof Checkbox || $component instanceof SelectList)) {
                $value = [];
            }

            $this->injectFormValue($component->getName(), $value);
        }
    }

    private function composeEmptyRepeatValue(HasComponents $component, ?string $locale = null): array
    {
        $emptyValue = [[]];

        foreach ($component->getComponents() as $nestedComponent) {

            if ($nestedComponent instanceof Repeat) {
                throw new \LogicException('Cannot compose nested Repeat component.');
            }

            if ($nestedComponent instanceof HasComponents) {
                // Kinda quirky, but it gets the job done.
                $emptyValue[0] = array_merge($emptyValue[0], $this->composeEmptyRepeatValue($nestedComponent, $locale)[0]);
            }

            // TODO: find better way to set default empty value... maybe a method on the component itself?
            if ($nestedComponent instanceof Checkbox || $nestedComponent instanceof SelectList) {
                $emptyValue[0][$nestedComponent->getName()] = [];
            }
        }

        return $emptyValue;
    }

    private function isEmptyRepeatValue($value): bool
    {
        return empty($value) || $value === [[]] || $value === [null];
    }

    private function injectFormValue(string $key, $value): void
    {
        Arr::set($this->form, $key, $value);
    }
}
