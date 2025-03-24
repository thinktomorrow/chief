<?php

namespace Thinktomorrow\Chief\Forms\Fields\Repeat\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Repeat\Repeat;
use Thinktomorrow\Chief\Forms\Livewire\WithDeeplyNestedArrays;

class RepeatComponent extends Component
{
    use HasForm;
    use WithDeeplyNestedArrays;

    //    use WithRepeatSections;

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

        /**
         * Inject all existing field values in the Livewire form object. Avoid a deeply
         * nested object so Livewire wire:model does not trip over itself.
         * From then on we can use the form object to access the values
         */
        $this->form = $field->getValue() ?: [];

        // get fields per section
        // Set up all sections fields
        // initialize wire:model -> form

        // PER LOCALE A DIFFERENT REPEAT or ....
        // IN REPEAT DIFFERENT LOCALES
        // Laat het werken in zowel LW (fragmnet edit) als in old sidebar / window form
    }

    public function getListeners()
    {
        return [
            'section-updated-'.$this->getId() => 'onSectionUpdated',
            'section-added-'.$this->getId() => 'onSectionAdded',
            'section-deleting-'.$this->getId() => 'onSectionDeleting',
        ];
    }

    public function getFormComponents(): array
    {
        return $this->field->getComponents();
    }

    public function prepareFormComponent($component, string $index): void
    {
        if (! $component instanceof Fields\Field) {
            if ($component instanceof HasComponents) {
                foreach ($component->getComponents() as $nestedComponent) {
                    $this->prepareFormComponent($nestedComponent, $index);
                }
            }

            return;
        }

        $component->setLocalizedFieldNameTemplate($index.'.:name.:locale');
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

    public function save()
    {
        dd($this->form);
        // No save - make sure that input fields are updated (cfr. file fields)
    }

    public function render()
    {
        return view('chief-form::livewire.repeat');
    }

    // fields
    // nested: could be repeat as well nested
    // add section
    // remove section
}
