<?php

namespace Thinktomorrow\Chief\Forms\Modals\Concerns;

use Illuminate\Support\Arr;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\AssetType\AssetTypeFactory;
use Thinktomorrow\Chief\Forms\Fields\Common\FormKey;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;
use Thinktomorrow\Chief\Forms\Livewire\LivewireFieldName;

trait InteractsWithForm
{
    public array $form = [];

    /**
     * Validation is performed for all fields
     * Each field is parsed for the proper validation rules and messages.
     */
    private function validateForm(array $rules = [], array $messages = [], array $validationAttributes = []): void
    {
        list($rules, $messages, $validationAttributes) = $this->createValidation($rules, $messages, $validationAttributes);

        /**
         * Livewire errors out when validation is run without any rules passed
         */
        if (! $rules) return;

        $this->validate($rules, $messages, $validationAttributes);
    }

    private function createValidation(array $rules, array $messages, array $validationAttributes): array
    {
        foreach ($this->getFieldsForValidation() as $field) {
            $validationParameters = ValidationParameters::make($field)->mapKeys(fn ($key) => LivewireFieldName::get($key));

            $rules = array_merge($rules, $validationParameters->getRules());
            $messages = array_merge($messages, $validationParameters->getMessages());
            $validationAttributes = array_merge($validationAttributes, $validationParameters->getAttributes());
        }

        return [$rules, $messages, $validationAttributes];
    }

    private function getFieldsForValidation(): array
    {
        return collect($this->getFields())
            ->reject(fn ($field) => ! $field instanceof Field)
            ->all();
    }
}
