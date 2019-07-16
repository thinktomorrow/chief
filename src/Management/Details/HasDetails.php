<?php

namespace Thinktomorrow\Chief\Management\Details;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\FlatReferences\ProvidesFlatReference;

trait HasDetails
{
    /**
     * Details of the model such as naming, key and class.
     * Used in several dynamic parts of the admin application.
     */
    public function details(): Details
    {
        // Generic model details
        // might be able to remove this as id isnt general info
        $id = Str::slug($this->registration->key().'-'.$this->model->id);
        $key = $this->registration->key();
        $labelSingular = property_exists($this->model, 'labelSingular') ? $this->model->labelSingular : Str::singular($key);
        $labelPlural = property_exists($this->model, 'labelPlural') ? $this->model->labelPlural : Str::plural($key);
        $internal_label = contract($this->model, ProvidesFlatReference::class) ? $this->model->flatReferenceLabel() : $key;

        // Manager index and header info
        $title = $this->model->title ?? ($this->model->id ? $labelSingular . ' ' . $this->model->id : $labelSingular);

        return new Details(
            $id,
            $key,
            $labelSingular,
            $labelPlural,
            $internal_label,
            $title
        );
    }
}
