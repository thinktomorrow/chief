<?php

namespace Thinktomorrow\Chief\Management\Details;

use Thinktomorrow\Chief\FlatReferences\ProvidesFlatReference;

trait HasManagedModelDetails
{
    /**
     * Details of the model such as naming, key and class.
     * Used in several dynamic parts of the admin application.
     */
    public function modelDetails(): ManagedModelDetails
    {
        // Generic model details 
        //might be able to remove this as id isnt general info
        $id = str_slug($this->registration->key().'-'.$this->model->id);
        $key = $this->registration->key();
        $labelSingular = property_exists($this, 'labelSingular') ? $this->labelSingular : ( (property_exists($this->model, 'labelSingular') && $this->model->labelSingular) ? $this->model->labelSingular : str_singular($key));
        $labelPlural = property_exists($this, 'labelPlural') ? $this->labelPlural : ( (property_exists($this->model, 'labelPlural') && $this->model->labelPlural) ? $this->model->labelPlural : str_plural($key));
        $internal_label = contract($this->model, ProvidesFlatReference::class) ? $this->model->flatReferenceLabel() : $key;

        // Manager index and header info
        $title = $this->model->title ?? ($this->model->id ? $labelSingular . ' ' . $this->model->id : $labelSingular);

        return new ManagedModelDetails(
            $id,
            $key,
            $labelSingular,
            $labelPlural,
            $internal_label,
            $title
        );
    }
}
