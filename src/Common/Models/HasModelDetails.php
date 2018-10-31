<?php

namespace Thinktomorrow\Chief\Common\Models;

trait HasModelDetails
{
    /**
     * Details of the model such as naming, key and class.
     * Used in several dynamic parts of the admin application.
     */
    public function modelDetails(): ModelDetails
    {
        $classKey = get_class($this);

        return new ModelDetails(
            $classKey,
            property_exists($this, 'labelSingular') ? $this->labelSingular : str_singular($classKey),
            property_exists($this, 'labelPlural') ? $this->labelPlural : str_plural($classKey),
            method_exists($this, 'flatReferenceLabel') ? $this->flatReferenceLabel() : $classKey
    );
    }
}