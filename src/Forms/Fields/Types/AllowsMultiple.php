<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Types;

trait AllowsMultiple
{
    protected bool $allowMultiple = false;

    /**
     * @return SelectField
     */
    public function multiple($flag = true): self
    {
        $this->allowMultiple = $flag;

        return $this;
    }

    public function allowMultiple(): bool
    {
        return $this->allowMultiple;
    }
}
