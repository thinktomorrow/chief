<?php

namespace Thinktomorrow\Chief\Assets\Livewire\Traits;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Forms\Fields\Common\FormKey;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Livewire\LivewireFieldName;

trait InteractsWithGroupedForms
{
    private function extractGroupedFormComponents()
    {
        foreach ($this->getGroupedComponents() as $components) {
            foreach ($components as $component) {
                if (! $component instanceof Field) {
                    continue;
                }

                Arr::set(
                    $this->form,
                    $component->getKey(),
                    // Keep the current form value if already present, else use the persisted value
                    data_get(
                        $this->form,
                        $component->getKey(),
                        data_get($this->previewFile->fieldValues, $component->getKey())
                    )
                );
            }
        }
    }

    public function getGroupedComponents(): array
    {
        return collect($this->componentIndices())
            ->mapWithKeys(function ($index) {

                $components = collect($this->getComponents())->map(function ($component) use ($index) {
                    $component->id(LivewireFieldName::getWithoutPrefix($component->getId(), null, $this->composeGroupIndex($index))); // For error rule matching
                    $component->key(LivewireFieldName::getWithoutPrefix($component->getKey(), null, $this->composeGroupIndex($index)));
                    $component->name(FormKey::replaceDotsByBrackets(LivewireFieldName::getWithoutPrefix($component->getName(), null, $this->composeGroupIndex($index))));

                    return $component;
                });

                return [$index => $components];
            })
            ->all();
    }

    /**
     * Allows to group the components per index. This is not used on a form that is set up
     * for one model / file. But can be used to display and handle fields that are dynamically
     * added or where there are more than one instance of, e.g. hotSpot forms.
     */
    private function componentIndices(): array
    {
        return [];
    }

    private function composeGroupIndex($index)
    {
        return $index;
    }
}
