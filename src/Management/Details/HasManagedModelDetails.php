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
        $classKey = get_class($this->model);
        $labelSingular = property_exists($this, 'labelSingular') ? $this->labelSingular : str_singular($classKey);
        $labelPlural = property_exists($this, 'labelPlural') ? $this->labelPlural : str_plural($classKey);
        $internal_label = contract($this->model, ProvidesFlatReference::class) ? $this->model->flatReferenceLabel() : $classKey;

        // Manager index and header info
        $title = $this->model->title ?? ($this->model->id ? $labelSingular . ' ' . $this->model->id : $labelSingular);
        $subtitle = '';
        $intro = '';

        return new ManagedModelDetails(
            $classKey,
            $labelSingular,
            $labelPlural,
            $internal_label,
            $title,
            $subtitle,
            $intro
        );
    }
}
