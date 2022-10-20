<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Concerns;

trait HasSortable
{
    protected bool $isSortable = false;

    public function isSortable(): bool
    {
        return $this->isSortable;
    }

    public function sortable(bool $isSortable = true): static
    {
        $this->isSortable = $isSortable;

        return $this;
    }
}
