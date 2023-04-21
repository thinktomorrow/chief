<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Thinktomorrow\Chief\Forms\Fields\Field;

trait HasParentField
{
    protected ?Field $parentField = null;

    public function parentField(Field $parentField): static
    {
        $this->parentField = $parentField;

        return $this;
    }

    public function getParentField(): ?int
    {
        return $this->parentField;
    }
}
