<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Forms\Fields\Field;

trait InteractsWithFields
{
    private function injectFormValues(iterable $components): void
    {
        foreach ($components as $component) {
            if (! $component instanceof Field) {
                continue;
            }

            if ($component->hasLocales()) {
                foreach ($component->getDottedLocalizedNames() as $locale => $name) {
                    $this->injectFormValue($name, $component->getValue($locale));
                }
            } else {
                dump($component->getValue());
                $this->injectFormValue($component->getName(), $component->getValue());
            }
        }
    }

    private function injectFormValue(string $key, $value): void
    {
        dump($key, $value);
        Arr::set($this->form, $key, $value);
    }
    //
    //    /**
    //     * Validation is performed for all fields
    //     * Each field is parsed for the proper validation rules and messages.
    //     */
    //    private function validateForm(array $rules = [], array $messages = [], array $validationAttributes = []): void
    //    {
    //        [$rules, $messages, $validationAttributes] = $this->addFormComponentValidation($rules, $messages, $validationAttributes);
    //
    //        $this->validate($rules, $messages, $validationAttributes);
    //    }
    //
    //    private function addFormComponentValidation(array $rules, array $messages, array $validationAttributes): array
    //    {
    //        foreach ($this->getFieldsForValidation() as $component) {
    //            $component->name(FieldNameHelpers::replaceDotsByBrackets(LivewireFieldName::get($component->getName())));
    //
    //            $validationParameters = ValidationParameters::make($component);
    //            $rules = array_merge($rules, $validationParameters->getRules());
    //            $messages = array_merge($messages, $validationParameters->getMessages());
    //            $validationAttributes = array_merge($validationAttributes, $validationParameters->getAttributes());
    //        }
    //
    //        return [$rules, $messages, $validationAttributes];
    //    }
    //
    //    // TODO: account for locales...
    //    private function getFieldsForValidation(): array
    //    {
    //        return collect($this->getComponents())
    //            ->reject(fn ($component) => ! $component instanceof Field)
    //            ->all();
    //    }
    //
    //    public function getComponents(): array
    //    {
    //        return array_map(function ($componentArray) {
    //            return $componentArray['class']::fromLivewire($componentArray);
    //        }, $this->components);
    //    }
    //
    //    protected function setComponents(array $components): void
    //    {
    //        $this->components = $this->initialComponents = array_map(fn ($component) => $component->toLivewire(), $components);
    //    }
    //
    //    private function syncForm()
    //    {
    //        $this->previewFile->fieldValues = array_merge($this->previewFile->fieldValues, $this->form);
    //
    //        $this->form['basename'] = $this->previewFile->getBaseName();
    //    }
    //
    //    private function addDefaultBasenameValidation(array $rules = [], array $messages = [], array $validationAttributes = []): array
    //    {
    //        $rules = array_merge($rules, ['form.basename' => ['required', 'min:1', 'max:200']]);
    //        $messages = array_merge($messages, []);
    //        $validationAttributes = array_merge($validationAttributes, ['form.basename' => 'bestandsnaam']);
    //
    //        return [$rules, $messages, $validationAttributes];
    //    }
}
