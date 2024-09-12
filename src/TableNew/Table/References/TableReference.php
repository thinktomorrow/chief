<?php

namespace Thinktomorrow\Chief\TableNew\Table\References;

use Livewire\Wireable;
use Thinktomorrow\Chief\TableNew\Table\Table;

class TableReference implements Wireable
{
    private string $resourceClass;
    private string $tableKey;

    /**
     * Unique Table reference.
     *
     * This is used to identify the table in Livewire Table component. For now we can use the resourceKey
     * and the fixed getIndexTable method. In a next phase, this will need to be the PageClass and
     * unique table key value.
     */
    public function __construct(string $resourceClass, string $tableKey)
    {
        $this->resourceClass = $resourceClass;
        $this->tableKey = $tableKey;
    }

    public function toLivewire()
    {
        return [
            'resourceClass' => $this->resourceClass,
            'tableKey' => $this->tableKey,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static($value['resourceClass'], $value['tableKey']);
    }

    public function getTable(): Table
    {
        $table = app($this->resourceClass)->{$this->tableKey}();
        $table->setTableReference($this);

        return $table;
    }

    public function getTableKey(): string
    {
        return $this->tableKey;
    }
}
