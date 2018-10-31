<?php

namespace Thinktomorrow\Chief\Common\Models;

trait HasPageModelDetails
{
    /**
     * Details of the model such as naming, key and class.
     * Used in several dynamic parts of the admin application.
     */
    public function modelDetails(): ModelDetails
    {
        $classKey = $this->morphKey();

        return new ModelDetails(
            $classKey,
            property_exists($this, 'labelSingular') ? $this->labelSingular : str_singular($classKey),
            property_exists($this, 'labelPlural') ? $this->labelPlural : str_plural($classKey),
            $this->flatReferenceLabel()
        );
    }
}