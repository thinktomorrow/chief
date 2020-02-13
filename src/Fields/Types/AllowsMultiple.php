<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

trait AllowsMultiple
{
    protected $allowMultiple = false;

    public function multiple($flag = true)
    {
        $this->allowMultiple = $flag;

        return $this;
    }

    public function allowMultiple(): bool
    {
        return $this->allowMultiple;
    }
}
