<?php

namespace Thinktomorrow\Chief\Forms;

trait HasFields
{
    public function getFields(): Fields
    {
        return Fields::extract($this->components);
    }
}
