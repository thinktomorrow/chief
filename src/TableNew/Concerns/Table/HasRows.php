<?php

namespace Thinktomorrow\Chief\TableNew\Concerns\Table;

use Closure;
use Illuminate\Support\Collection;

trait HasRows
{
    protected null|Collection|Closure $rows = null;

    public function rows(Collection|Closure $rows): static
    {
        $this->rows = $rows;

        return $this;
    }

    public function getRows(): ?Collection
    {
        $rows = $this->rows;

        if (is_callable($rows)) {
            $rows = call_user_func_array($rows, [$this, $this->getModel()]);
        }

        return $rows;
    }
}
