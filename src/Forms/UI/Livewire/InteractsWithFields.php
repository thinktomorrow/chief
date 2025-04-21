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
                $value = ($component instanceof Repeat && $this->isEmptyRepeatValue($component->getValue($locale)))
                    ? $this->composeEmptyRepeatValue($component, $locale)
                    : $component->getValue($locale);

                $this->injectFormValue($name, $value);
            }
        } else {
            $value = ($component instanceof Repeat && $this->isEmptyRepeatValue($component->getValue()))
                ? $this->composeEmptyRepeatValue($component)
                : $component->getValue();

            $this->injectFormValue($component->getName(), $value);
        }
    }

    private function composeEmptyRepeatValue(Repeat $component, ?string $locale = null): array
    {
        $emptyValue = [[]];

        foreach ($component->getComponents() as $nestedComponent) {
            if ($nestedComponent instanceof Repeat) {
                $emptyValue[0][$nestedComponent->getName()] = $this->composeEmptyRepeatValue($nestedComponent, $locale);
            }
        }

        return $emptyValue;
    }

    //    private function prepareForSaving(array $form): array
    //    {
    //        foreach ($form as $key => $value) {
    //            if (is_array($value)) {
    //                $form[$key] = $this->prepareForSaving($value);
    //            }
    //
    //            if ($this->isEmptyRepeatValue($value)) {
    //                $form[$key] = null;
    //            }
    //        }
    //
    //        return $form;
    //    }
    //

    private function isEmptyRepeatValue($value): bool
    {
        return empty($value) || $value === [[]] || $value === [null];
    }

    private function injectFormValue(string $key, $value): void
    {
        Arr::set($this->form, $key, $value);
    }
}
