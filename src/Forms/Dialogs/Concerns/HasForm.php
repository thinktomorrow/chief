<?php

namespace Thinktomorrow\Chief\Forms\Dialogs\Concerns;

use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;

trait HasForm
{
    public array $form = [];

    public function addFormData(array $data): void
    {
        $this->form = array_merge($this->form, $data);
    }

    public function setFormData(array $data): void
    {
        $this->form = $data;
    }

    public function getFormData(): array
    {
        return $this->form;
    }

    public function getFormValue(string $key): mixed
    {
        return data_get($this->form, $key);
    }

    public function setFormValue(string $key, mixed $value): void
    {
        data_set($this->form, $key, $value);
    }

    /**
     * Validation is performed for all fields
     * Each field is parsed for the proper validation rules and messages.
     */
    private function validateForm(array $rules = [], array $messages = [], array $validationAttributes = []): void
    {
        [$rules, $messages, $validationAttributes] = $this->createValidation($rules, $messages, $validationAttributes);

        if (! $rules) {
            return;
        }

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
