<?php

namespace Thinktomorrow\Chief\Forms\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Repeat;

class RepeatComponent extends Component
{
    use HasForm;

    public Repeat $field;

    public ?string $parentComponentId;

    public ?string $locale = null;

    public string $elementId;

    public function mount(Repeat $field, ?string $locale = null, ?string $parentComponentId = null)
    {
        $this->field = $field;

        $this->parentComponentId = $parentComponentId;
        $this->locale = $locale;
        $this->elementId = $field->getElementId($locale);
    }

    public function getListeners()
    {
        return [
            //
        ];
    }

    public function getFormComponents(): array
    {
        return $this->field->getComponents();
    }

    public function prepareFormComponent($component, string $index): void
    {
        if (! $component instanceof Field) {
            if ($component instanceof HasComponents) {
                foreach ($component->getComponents() as $nestedComponent) {
                    $this->prepareFormComponent($nestedComponent, $index);
                }
            }

            return;
        }

        $component->setFieldNameTemplate($index.'.:name.:locale');
    }

    public function addSection(): void
    {
        // Get all entries from last section and use it for a new (empty) entry
        $firstEntry = $this->form[0];

        $this->form[] = $this->clearValues($firstEntry);
    }

    public function removeSection(int $index): void
    {
        unset($this->form[$index]);

        $this->form = array_values($this->form);
    }

    public function reorder($indices)
    {
        // Reorder form to match given indices sequence
        $this->form = array_map(function ($index) {
            return $this->form[$index];
        }, $indices);
    }

    private function clearValues(array $values): array
    {
        return array_map(function ($value) {
            return is_array($value) ? $this->clearValues($value) : null;
        }, $values);
    }

    public function render()
    {
        return view('chief-form::livewire.repeat');
    }
}
