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
        $className = ($this->modelClass());
        $genericModelInstance = new $className;

        // Generic model details
        $id = Str::slug($this->registration->key(). ($this->hasExistingModel() ? '-'. $this->existingModel()->id : ''));
        $key = $this->registration->key();
        $labelSingular = property_exists($genericModelInstance, 'labelSingular') ? $genericModelInstance->labelSingular : Str::singular($key);
        $labelPlural = property_exists($genericModelInstance, 'labelPlural') ? $genericModelInstance->labelPlural : Str::plural($key);
        $internal_label = ($this->hasExistingModel() && contract($this->model, ProvidesFlatReference::class))
            ? $this->existingModel()->flatReferenceLabel()
            : $key;

        // Manager index and header info
        $title = ($this->hasExistingModel() && $this->existingModel()->title)
            ? $this->existingModel()->title
            : $labelSingular;

        return new Details(
            $id,
            $key,
            $labelSingular.'',
            $labelPlural.'',
            $internal_label,
            $title.''
        );
    }
}
