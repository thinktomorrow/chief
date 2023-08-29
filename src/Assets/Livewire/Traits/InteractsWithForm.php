<?php

namespace Thinktomorrow\Chief\Assets\Livewire\Traits;

use Illuminate\Support\Arr;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Forms\Fields\Common\FormKey;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;
use Thinktomorrow\Chief\Forms\Livewire\LivewireFieldName;

trait InteractsWithForm
{
    public $form = [];
    public $components = []; // The initial components + the generic ones of the Asset combined
    public $initialComponents = []; // The initial components as passed to this class

    /**
     * Add fields from a specific method from the Asset class.
     */
    protected function addAssetComponents(string $method = 'fields')
    {
        if ($this->previewFile->mediaId) {
            $asset = Asset::find($this->previewFile->mediaId);

            if (method_exists($asset, $method)) {
                $this->components = [
                    ...$this->initialComponents,
                    ...array_map(fn ($component) => $component->toLivewire(), iterator_to_array($asset->{$method}())),
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

            Arr::set(
                $this->form,
                $component->getKey(),
                data_get($this->previewFile->fieldValues, $component->getKey())
            );
        }
    }

    /**
     * Validation is performed for all fields
     * Each field is parsed for the proper validation rules and messages.
     */
    private function validateForm(array $rules = [], array $messages = [], array $validationAttributes = []): void
    {
        list($rules, $messages, $validationAttributes) = $this->addFormComponentValidation($rules, $messages, $validationAttributes);

        $this->validate($rules, $messages, $validationAttributes);
    }

    private function addFormComponentValidation(array $rules, array $messages, array $validationAttributes): array
    {
        foreach ($this->getFieldsForValidation() as $component) {
            $component->name(FormKey::replaceDotsByBrackets(LivewireFieldName::get($component->getName())));

            $validationParameters = ValidationParameters::make($component);
            $rules = array_merge($rules, $validationParameters->getRules());
            $messages = array_merge($messages, $validationParameters->getMessages());
            $validationAttributes = array_merge($validationAttributes, $validationParameters->getAttributes());
        }

        return [$rules, $messages, $validationAttributes];
    }

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

    private function addDefaultBasenameValidation(array $rules = [], array $messages = [], array $validationAttributes = []): array
    {
        $rules = array_merge($rules, ['form.basename' => ['required', 'min:1', 'max:200']]);
        $messages = array_merge($messages, []);
        $validationAttributes = array_merge($validationAttributes, ['form.basename' => 'bestandsnaam']);

        return [$rules, $messages, $validationAttributes];
    }
}
