<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

use Thinktomorrow\Chief\TableNew\Table\TableReference;

trait HasTableReference
{
    private ?TableReference $tableReference = null;

    public function setTableReference(TableReference $tableReference): static
    {
        $this->tableReference = $tableReference;

        return $this;
    }

    public function getTableReference(): TableReference
    {
        if(! $this->tableReference) {
            throw new \Exception('Table reference not set.');
        }

        return $this->tableReference;
    }
}
