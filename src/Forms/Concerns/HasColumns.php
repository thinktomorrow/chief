<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasColumns
{
    protected int $columns = 1;

    public function columns(int $columns): static
    {
        $this->columns = $columns;

        return $this;
    }

    public function getColumns(): int
    {
        return $this->columns;
    }
}
