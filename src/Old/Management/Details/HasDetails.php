<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Old\Management\Details;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

trait HasDetails
{
    /**
     * Details of the model such as naming, key and class.
     * Used in several dynamic parts of the admin application.
     */
    public function details(): Details
    {
        $genericModelInstance = $this->modelInstance();

        // Generic model details
        $id = Str::slug($this->registration->key() . ($this->hasExistingModel() ? '-' . $this->existingModel()->id : ''));
        $key = $this->registration->key();
        $labelSingular = property_exists($genericModelInstance, 'labelSingular') ? $genericModelInstance->labelSingular : Str::singular($key);
        $labelPlural = property_exists($genericModelInstance, 'labelPlural') ? $genericModelInstance->labelPlural : Str::plural($key);
        $internal_label = ($this->hasExistingModel() && contract($this->model, ReferableModel::class))
            ? $this->existingModel()->modelReferenceLabel()
            : $key;

        // Manager index and header info
        $title = ($this->hasExistingModel() && $this->existingModel()->title)
            ? $this->existingModel()->title
            : $labelSingular;

        return new Details(
            $id,
            $key,
            $labelSingular . '',
            $labelPlural . '',
            $internal_label,
            $title . ''
        );
    }
}
