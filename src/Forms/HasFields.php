<?php

namespace Thinktomorrow\Chief\Forms;

use Thinktomorrow\Chief\Forms\Fields\Field;

trait HasFields
{
    public function getFields(): Fields
    {
        return Fields::extract($this->components);
    }
}
