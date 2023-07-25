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

    protected function addAssetComponents()
    {
        if($this->previewFile->mediaId) {
            $asset = Asset::find($this->previewFile->mediaId);

            if(method_exists($asset, 'fields')) {
                $this->components = [
                    ...$this->initialComponents,
                    ...array_map(fn ($component) => $component->toLivewire(), iterator_to_array(Asset::find($this->previewFile->mediaId)->fields())),
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
    private function validateForm(): void
    {
        $rules = ['form.basename' => ['required', 'min:1', 'max:200']];
        $messages = [];
        $validationAttributes = ['form.basename' => 'bestandsnaam'];

        list($rules, $messages, $validationAttributes) = $this->addFormComponentValidation($rules, $messages, $validationAttributes);

        $this->validate($rules, $messages, $validationAttributes);
    }

    private function addFormComponentValidation(array $rules, array $messages, array $validationAttributes): array
    {
        foreach ($this->getComponents() as $component) {
            if ($component instanceof Field) {

                $component->name(FormKey::replaceDotsByBrackets(LivewireFieldName::get($component->getName())));

                $validationParameters = ValidationParameters::make($component);
                $rules = array_merge($rules, $validationParameters->getRules());
                $messages = array_merge($messages, $validationParameters->getMessages());
                $validationAttributes = array_merge($validationAttributes, $validationParameters->getAttributes());
            }
        }

        return [$rules, $messages, $validationAttributes];
    }
}
