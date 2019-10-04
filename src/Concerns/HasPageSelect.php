<?php

namespace Thinktomorrow\Chief\Concerns;

use Thinktomorrow\Chief\FlatReferences\FlatReferenceFactory;

trait HasPageSelect
{
    public function pageLink($column, $locale = null){
        return FlatReferenceFactory::fromString($this->$column)->instance()->url($locale ?? app()->getLocale());
    }
}
