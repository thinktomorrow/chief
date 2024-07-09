<?php

namespace Thinktomorrow\Chief\TableNew\Concerns\Table;

use Thinktomorrow\Chief\TableNew\TableReference;

trait HasTableReference {
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
