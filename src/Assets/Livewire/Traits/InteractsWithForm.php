<?php

namespace Thinktomorrow\Chief\Assets\Livewire\Traits;

use Illuminate\Support\Arr;
use Thinktomorrow\AssetLibrary\AssetType\AssetTypeFactory;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\FieldName\FieldNameHelpers;
use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;

trait InteractsWithForm
{
    use InteractsWithBasename;

    public $form = [];

    public $components = []; // The initial components + the generic ones of the Asset combined

    public $initialComponents = []; // The initial components as passed to this class

    /**
     * Add fields from a specific method from the Asset class.
     */
    protected function addInitialComponents()
    {
        $this->components = $this->initialComponents;
    }

    /**
     * Add fields from a specific method from the Asset class.
     */
    protected function addAssetComponents(string $method = 'fields')
    {
        if ($this->previewFile->assetType) {
            $genericAssetInstance = AssetTypeFactory::instance($this->previewFile->assetType);
            if (method_exists($genericAssetInstance, $method)) {
                $this->components = [
                    ...$this->components,
                    ...array_map(fn ($component) => $component->toLivewire(), iterator_to_array($genericAssetInstance->{$method}())),
                ];
            }
        }
    }

    private function extractFormComponents()
    {
        foreach ($this->components as $componentArray) {
            $component = $componentArray['class']::fromLivewire($componentArray);
            if (! $component instanceof Field) {
                continue;
            }

            if ($component->hasLocales()) {
                foreach ($component->getDottedLocalizedNames() as $name) {
                    $this->injectFormValue($name, data_get($this->previewFile->fieldValues, $name));
                }
            } else {
                $this->injectFormValue($component->getName(), data_get($this->previewFile->fieldValues, $component->getName()));
            }
        }
    }

    private function injectFormValue(string $key, $value): void
    {
        Arr::set($this->form, $key, $value);
    }

    /**
     * Validation is performed for all fields
     * Each field is parsed for the proper validation rules and messages.
     */
    private function validateForm(array $rules = [], array $messages = [], array $validationAttributes = []): void
    {
        [$rules, $messages, $validationAttributes] = $this->addFormComponentValidation($rules, $messages, $validationAttributes);

        $this->validate($rules, $messages, $validationAttributes);
    }

    private function addFormComponentValidation(array $rules, array $messages, array $validationAttributes): array
    {
        foreach ($this->getFieldsForValidation() as $component) {
            $component->name(FieldNameHelpers::replaceDotsByBrackets(LivewireFieldName::get($component->getName())));

            $validationParameters = ValidationParameters::make($component);
            $rules = array_merge($rules, $validationParameters->getRules());
            $messages = array_merge($messages, $validationParameters->getMessages());
            $validationAttributes = array_merge($validationAttributes, $validationParameters->getAttributes());
        }

        return [$rules, $messages, $validationAttributes];
    }

    // TODO: account for locales...
    private function getFieldsForValidation(): array
    {
        return collect($this->getComponents())
            ->reject(fn ($component) => ! $component instanceof Field)
            ->all();
    }

    public function getComponents(): array
    {
        return array_map(function ($componentArray) {
            return $componentArray['class']::fromLivewire($componentArray);
        }, $this->components);
    }

    protected function setComponents(array $components): void
    {
        $this->components = $this->initialComponents = array_map(fn ($component) => $component->toLivewire(), $components);
    }

    private function syncForm()
    {
        $this->previewFile->fieldValues = array_merge($this->previewFile->fieldValues, $this->form);

        $this->form['basename'] = $this->previewFile->getBaseName();
    }
}
