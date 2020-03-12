<?php

namespace Thinktomorrow\Chief\Concerns;

use Thinktomorrow\Chief\FlatReferences\FlatReferenceFactory;

trait HasPageSelect
{
    public function convertPagefieldvaluesToLinks($value, $locale = null)
    {
        return FlatReferenceFactory::fromString($value)->instance()->url($locale ?? app()->getLocale());
    }
}
