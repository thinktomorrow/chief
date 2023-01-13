<?php

namespace Thinktomorrow\Chief\Forms;

use Thinktomorrow\Chief\Forms\Fields\Repeat;

trait HasFields
{
    public function getFields(): Fields
    {
        // Return all fields but omit any nested fields such as there are in the repeat field
        return Fields::make($this->components, fn ($field) => ! $field instanceof Repeat);
    }

    public function getAllFields(): Fields
    {
        return Fields::make($this->components);
    }
}
