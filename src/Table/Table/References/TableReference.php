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
            'parameters' => $this->prepareParametersToLivewire(),
        ];
    }

    public static function fromLivewire($value)
    {
        return new static($value['resourceClass'], $value['tableKey'], static::restoreParametersFromLivewire($value['parameters']));
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

    private function prepareParametersToLivewire(): array
    {
        $parameters = $this->parameters;

        // If any of the parameters are wireable, we need to convert them to array.
        foreach ($parameters as $key => $parameter) {
            if ($parameter instanceof Wireable) {
                $parameters[$key] = $parameter->toLivewire();
            }
        }

        return $parameters;
    }

    private static function restoreParametersFromLivewire(array $parameters): array
    {
        foreach ($parameters as $key => $parameter) {
            if (is_array($parameter) && isset($parameter['class']) && class_exists($parameter['class']) && in_array(Wireable::class, class_implements($parameter['class']))) {
                $parameters[$key] = $parameter['class']::fromLivewire($parameter);
            } elseif (is_array($parameter) && key($parameter) == 'model-reference') {
                // Special case for ModelReference, as it does not have a 'class' key.
                $parameters[$key] = \Thinktomorrow\Chief\Shared\ModelReferences\ModelReference::fromLivewire($parameter);
            }
        }

        return $parameters;
    }
}
