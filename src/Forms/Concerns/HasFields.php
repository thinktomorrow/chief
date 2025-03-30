<?php

namespace Thinktomorrow\Chief\Forms\Concerns;

use Thinktomorrow\Chief\Forms\App\Queries\Fields;

trait HasFields
{
    public function getFields(): Fields
    {
        return Fields::makeWithoutFlatteningNestedFields($this->components);
    }

    public function getAllFields(): Fields
    {
        return Fields::make($this->components);
    }
}
