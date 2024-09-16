<?php

namespace Thinktomorrow\Chief\Table\Actions\Concerns;

trait HasRefresh
{
    protected bool $refreshTable = false;

    public function refreshTable(bool $refresh = true): static
    {
        $this->refreshTable = $refresh;

        return $this;
    }

    public function shouldRefreshTable(): bool
    {
        return $this->refreshTable;
    }
}
