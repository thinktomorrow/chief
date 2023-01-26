<?php

namespace Thinktomorrow\Chief\Forms\Livewire;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Component;
use Thinktomorrow\Chief\Forms\Query\FindForm;

class Repeat extends Component
{
    use HasWiredInput;

    public Model $model;
    public string $formId;
    public string $fieldKey;

    public $count = 0;

    public $listeners = [
        'repeatValuesChanged',
    ];

    /**
     * The form data contained by each field
     * @var array
     */
//    public array $formData = [];

    public function mount($model, string $formId, string $fieldKey)
    {
        $this->model = $model;
        $this->formId = $formId;
        $this->fieldKey = $fieldKey;

//        $this->populateFormData();

//        dd($field);
        // populate
    }

    private function populateFormData()
    {
        // TODO: assign wirekey to each entry maybe as key so DOM update is done as expected.
        // Set the name so it corresponds with the default formData sync behavior
        data_set($this->formData, $this->getPrefixedFieldKey(), $this->field->getActiveValue() ?? [ $this->getDefaultRow() ]);

//        $this->formData = [$this->fieldKey => ];
if($this->fieldKey == 'inner') {
    dd($this->field->getValue());

        dd($this->formData);

}
//        if($this->fieldKey != 'labels') {
//            dd($this->fieldKey);
//        }
    }

    private function getPrefixedFieldKey(): string
    {
        return isset($this->prefix) ? $this->prefix.'.'.$this->fieldKey : $this->fieldKey;
    }

    private function getDefaultRow(): array
    {
        $row = [];

        foreach($this->field->getComponents() as $component) {
            $row[$component->getId()] = $component->getValue();
        }

        return $row;
    }

    // values (can be localized)
    // add
    // remove
    // sort
    // max?

    public function getFieldProperty(): \Thinktomorrow\Chief\Forms\Fields\Repeat
    {
        /** @var \Thinktomorrow\Chief\Forms\Form $form */
        $form = app(FindForm::class)->findByModel($this->model, $this->formId);
$this->count++;
        $field = $form->getAllFields()->find($this->fieldKey);

        // In an active session, the formData property is the true value state, instead of the initial value state.
        if(count($this->formData) > 0) {
            $field->value($this->getRows());
        }

        return $field;
    }

    // Needed because we can have nested repeats and $this->form is passed on to these nested repeat livewire instances
    public function getFormProperty()
    {
        /** @var \Thinktomorrow\Chief\Forms\Form $form */
        $form = app(FindForm::class)->findByModel($this->model, $this->formId);

        return $form;
    }

    public function addRow()
    {
        // Take the first row and copy it?
        $this->formData[$this->fieldKey][] = $this->newRow();

        $this->field->value($this->getRows());
    }

    private function newRow(): array
    {
        $newRow = $this->getRows()[0];

        // TODO: get defaults from fields instead of the real first defaults
        return array_fill_keys(array_keys($newRow), null);
    }

    public function removeRow($index)
    {
        unset($this->formData[$this->fieldKey][$index]);

        array_values($this->formData[$this->fieldKey]);

        $this->syncWithForm();
    }

    public function sortRows()
    {
        // Is this perhaps automatically?
    }

    public function render()
    {
        return view('chief-form::livewire.repeat');
    }

    public function updatedFormData()
    {
        $this->syncWithForm();
    }

    private function getRows(): array
    {
        return $this->formData[$this->fieldKey]; // because it is always an array with one item, being the repeat field name as key
    }

    private function syncWithForm()
    {
        $this->emitUp('repeatValuesChanged',
            $this->getPrefixedFieldKey(),
            $this->getRows()
        );
    }

    public function repeatValuesChanged(string $fieldId, array $values)
    {
        if(!isset($this->formData[$fieldId])) {
            dd($this->formData, $fieldId);
            throw new \InvalidArgumentException('No nested repeat formdata key ['.$fieldId.'] found in the form.');
        }

        $this->formData[$fieldId] = $values;
    }
}
