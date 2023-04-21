<?php

namespace Thinktomorrow\Chief\Forms\Livewire;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Component;
use Thinktomorrow\Chief\Forms\Fields\Common\FormKey;
use Thinktomorrow\Chief\Forms\Fields\Common\LocalizedFormKey;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Query\FindForm;


class Form extends Component
{
    use HasWiredInput;

    public $model;
    public string $formId;

    public $message;

    public $count = 0;

    /**
     * The form data contained by each field
     * @var array
     */
    public array $formData = [];

    public string $formHash;

    public $listeners = [
//        'repeatValuesChanged',
    ];

    public function mount($model, string $formId)
    {
        $this->model = $model;
        $this->formId = $formId;

        $this->formHash = Str::random();

        $this->populateFormData();
    }

    public function getFormProperty()
    {
        /** @var \Thinktomorrow\Chief\Forms\Form $form */
        $form = app(FindForm::class)->findByModel($this->model, $this->formId);

        $form->elementId($form->getId() . $this->formHash);

        // Trigger the attributes population of the form component
        // We do it here so each render does not trigger full form refresh. (which causes input field to loose focus)
        $form->data();

        // TODO: set fixed elementIds so we dont trigger a refresh when elementId has changed.
        // TODO: set unique element ids... (once and not on every create...)
$this->count++;
        return $form;

    }

    private function populateFormData()
    {
        // TODO: localizedFormKey should be always <KEY><LOCALE> (no longer trans[nl][title])
//        LocalizedFormKey::setDefaultTemplate(':name.:locale');

        // TODO: ideally we should take the current value from the field itself
        // ALSO: account for localisation, repeatfield, file field, ...
        foreach($this->form->getFields() as $field){
            $this->addFormDataEntry($field);
        }
    }

    private function addFormDataEntry(Field $field)
    {
        if($field->hasLocales()) {
            foreach($field->getLocales() as $locale) {
                data_set($this->formData,$this->formDataIdentifierSegment($field->getName(), $locale), $field->getActiveValue($locale));
            }
        } else {
            data_set($this->formData,$this->formDataIdentifierSegment($field->getName()), $field->getActiveValue());
        }
    }

    public function repeatValuesChanged(string $fieldId, array $values)
    {
        if(!isset($this->formData[$fieldId])) {
            throw new \InvalidArgumentException('No formdata key ['.$fieldId.'] found in the form.');
        }

        $this->formData[$fieldId] = $values;
    }

//    public function populateFormData($key, $value)
//    {
//        // TODO: this breaks the sync...
//        $this->formData[$key] = $value;
//
////        // TODO: ideally we should take the current value from the field itself
////        // ALSO: account for localisation, repeatfield, file field, ...
////        foreach($this->form->getFields() as $field){
////            $this->formData[$field->getName()] = $field->getActiveValue();
////        }
//    }

    public function render()
    {
//        $this->formData['title'] = 'qqkkqkq';
//        $this->handleHydrateProperty('formData.title', 'qqqqqqq');
        // Trigger the attributes population of the form component

//        $this->populateFormData();

        // populate the formData property? Or should this be done via the field component render itself
        // ...

        return view('chief-form::livewire.form');
    }

    public function save()
    {
        sleep(2);
        dd($this->formData);
        // Get all input...
        // Validate input...
        // Save form -> event push
        // feedback to user

        // saving it...
        dd($this->form); // computed property???

        // Store the form ...

    }
}
