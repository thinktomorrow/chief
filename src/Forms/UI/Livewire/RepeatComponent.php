<?php

namespace Thinktomorrow\Chief\Forms\UI\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Repeat;

class RepeatComponent extends Component
{
    use HasForm;
    use InteractsWithFields;

    public Repeat $field;

    public ?string $parentComponentId = null;

    public ?string $locale = null;

    public string $elementId;

    public array $rowUids = [];

    public int $formRefreshKey = 0;

    public function mount(Repeat $field, ?string $locale = null, ?string $parentComponentId = null)
    {
        $this->field = $field;

        $this->parentComponentId = $parentComponentId;
        $this->locale = $locale;
        $this->elementId = $field->getElementId($locale);

        $this->syncRowUidsWithForm();
    }

    public function updatedForm(): void
    {
        $this->syncRowUidsWithForm();
    }

    public function getListeners()
    {
        return [
            //
        ];
    }

    public function getFormComponents(): array
    {
        return $this->applyFieldDependencies($this->field->getComponents());
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
        $component->enableWireModelLive();
    }

    public function addSection(): void
    {
        // Get all entries from first section and use it for a new (empty) entry
        $section = $this->form[0];

        $this->form[] = $this->clearValues($section);
        $this->rowUids[] = $this->newRowUid();

        // Emit event to trigger redactor initialization
        $this->dispatch('form-dialog-opened', ...[
            'componentId' => $this->getId(),
            'parentComponentId' => $this->parentComponentId,
        ]);
    }

    public function removeSection(int $index): void
    {
        unset($this->form[$index]);
        unset($this->rowUids[$index]);

        $this->form = array_values($this->form);
        $this->rowUids = array_values($this->rowUids);
        $this->formRefreshKey++;
    }

    public function reorder($indices)
    {
        // Reorder form to match given indices sequence
        $this->form = array_map(function ($index) {
            return $this->form[$index];
        }, $indices);

        $this->rowUids = array_map(function ($index) {
            return $this->rowUids[$index] ?? $this->newRowUid();
        }, $indices);

        $this->formRefreshKey++;
    }

    public function getRowUid(int|string $index): string
    {
        $index = (int) $index;

        return $this->rowUids[$index] ?? (string) $index;
    }

    private function clearValues(array $values): array
    {
        return array_map(function ($value) {
            return is_array($value) ? $this->clearValues($value) : null;
        }, $values);
    }

    private function syncRowUidsWithForm(): void
    {
        $count = is_array($this->form) ? count($this->form) : 0;

        $this->rowUids = array_slice(array_values($this->rowUids), 0, $count);

        while (count($this->rowUids) < $count) {
            $this->rowUids[] = $this->newRowUid();
        }
    }

    private function newRowUid(): string
    {
        return (string) Str::uuid();
    }

    public function render()
    {
        return view('chief-form::livewire.repeat');
    }
}
