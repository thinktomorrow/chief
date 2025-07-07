<?php

namespace Thinktomorrow\Chief\Table\Table\References;

use Livewire\Wireable;
use Thinktomorrow\Chief\Table\Table;

class TableReference implements Wireable
{
    private string $resourceClass;

    private string $tableKey;

    private array $parameters;

    /**
     * Unique Table reference.
     *
     * This is used to identify the table in Livewire Table component. For now we can use the resourceKey
     * and the fixed getIndexTable method. In a next phase, this will need to be the PageClass and
     * unique table key value.
     */
    public function __construct(string $resourceClass, string $tableKey, $parameters = [])
    {
        $this->resourceClass = $resourceClass;
        $this->tableKey = $tableKey;
        $this->parameters = (array) $parameters;
    }

    public function toLivewire()
    {
        return [
            'resourceClass' => $this->resourceClass,
            'tableKey' => $this->tableKey,
            'parameters' => $this->parameters,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static($value['resourceClass'], $value['tableKey'], $value['parameters']);
    }

    public function getTable(): Table
    {
        $table = app($this->resourceClass)->{$this->tableKey}(...$this->parameters);

        if (! $table instanceof Table) {
            throw new \RuntimeException('The table method ['.$this->resourceClass.'::'.$this->tableKey.'] in the TableReference should return a Table instance.');
        }

        $table->setTableReference($this);

        return $table;
    }

    public function getTableKey(): string
    {
        return $this->tableKey;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function __toString(): string
    {
        return $this->resourceClass.'::'.$this->tableKey;
    }

    public function toUniqueString(): string
    {
        return $this->resourceClass.'::'.$this->tableKey.'?params='.implode('|', $this->parameters);
    }
}
