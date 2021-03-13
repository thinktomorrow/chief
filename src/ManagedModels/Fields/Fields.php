<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields;

use ArrayIterator;
use Thinktomorrow\Chief\ManagedModels\Fields\Field;

class Fields implements \ArrayAccess, \IteratorAggregate, \Countable
{
    private array $fields;

    final public function __construct(array $fields = [])
    {
        $this->validateFields($fields);

        $this->fields = $this->convertToKeyedArray($fields);
    }

    /**
     * @return static
     */
    public static function make(iterable $generator): self
    {
        $fields = [];
        foreach($generator as $field) {
            $fields[] = $field;
        }

        return new static($fields);
    }

    public function all(): array
    {
        return $this->fields;
    }

    public function first(): ?Field
    {
        if (! $this->any()) {
            return null;
        }

        return reset($this->fields);
    }

    public function find(string $key): Field
    {
        if (! isset($this->fields[$key])) {
            throw new \InvalidArgumentException('No field found by key ' . $key);
        }

        return $this->fields[$key];
    }

    public function any(): bool
    {
        return count($this->all()) > 0;
    }

    public function isEmpty(): bool
    {
        return ! $this->any();
    }

    public function keys(): array
    {
        return array_keys($this->fields);
    }

    /**
     * Clone method is needed because Field as an object has mutable state. This is something that we
     * should try to avoid and fix the field object to an object of immutable state instead.
     *
     * @return Fields
     */
    public function clone(): Fields
    {
        $clonedFields = [];

        foreach ($this->fields as $field) {
            $clonedFields[] = clone $field;
        }

        return new static($clonedFields);
    }

    public function map(callable $callback): Fields
    {
        $keys = array_keys($this->fields);

        $items = array_map($callback, $this->fields, $keys);

        return new static(array_combine($keys, $items));
    }

    public function model($model): self
    {
        return $this->map(function ($field) use ($model) {
            return $field->model($model);
        });
    }

    public function component($componentKey): Fields
    {
        return $this->filterBy(function ($field) use ($componentKey) {
            return $field->componentKey() === $componentKey;
        });
    }

    public function groupByComponent(): array
    {
        $fields = [];

        foreach ($this->fields as $field) {
            if (! isset($fields[$field->componentKey()])) {
                $fields[$field->componentKey()] = new static();
            }

            $fields[$field->componentKey()] = $fields[$field->componentKey()]->add($field);
        }

        return $fields;
    }

    /**
     * @param \Closure|string $key
     * @param null|mixed $value
     *
     * @return static
     */
    public function filterBy($key, $value = null): self
    {
        $fields = [];

        foreach ($this->fields as $field) {
            if ($key instanceof \Closure) {
                if (true == $key($field)) {
                    $fields[] = $field;
                }

                continue;
            }

            $method = 'get' . ucfirst($key);

            // Reject from list if value does not match expected one
            if ($value && $value == $field->$method()) {
                $fields[] = $field;
            } // Reject from list if key returns null (key not present on field)
            elseif (! $value && ! is_null($field->$method())) {
                $fields[] = $field;
            }
        }

        return new static($fields);
    }

    public function render(): string
    {
        return array_reduce($this->fields, function (string $carry, Field $field) {
            return $carry . $field->render();
        }, '');
    }

    public function keyed($key): Fields
    {
        $keys = (array) $key;

        return new static(array_filter($this->fields, function (Field $field) use ($keys) {
            return in_array($field->getKey(), $keys);
        }));
    }

    public function tagged($tag): Fields
    {
        return new static(array_filter($this->fields, function (Field $field) use ($tag) {
            return $field->tagged($tag);
        }));
    }

    public function notTagged($tag): Fields
    {
        return new static(array_filter($this->fields, function (Field $field) use ($tag) {
            return ! $field->tagged($tag);
        }));
    }

    public function untagged(): Fields
    {
        return new static(array_filter($this->fields, function (Field $field) {
            return $field->untagged();
        }));
    }

    public function add(Field ...$fields): Fields
    {
        return new Fields(array_merge($this->fields, $fields));
    }

    public function merge(Fields $fields): Fields
    {
        return new Fields(array_merge($this->fields, $fields->all()));
    }

    /**
     * @return static
     */
    public function remove($keys = null): self
    {
        if (! $keys) {
            return $this;
        }

        if (is_string($keys)) {
            $keys = func_get_args();
        }

        foreach ($this->fields as $k => $field) {
            if (in_array($field->getKey(), $keys)) {
                unset($this->fields[$k]);
            }
        }

        return $this;
    }

    public function offsetExists($offset)
    {
        return isset($this->fields[$offset]);
    }

    public function offsetGet($offset)
    {
        if (! isset($this->fields[$offset])) {
            throw new \RuntimeException('No field found by key [' . $offset . ']');
        }

        return $this->fields[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (! $value instanceof Field) {
            throw new \InvalidArgumentException('Passed value must be of type ' . Field::class);
        }

        $this->fields[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->fields[$offset]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->fields);
    }

    private function convertToKeyedArray(array $fields): array
    {
        $keyedFields = [];

        /** @var Field */
        foreach ($fields as $field) {
            $keyedFields[$field->getKey()] = $field;
        }

        return $keyedFields;
    }

    private function validateFields(array $fields): void
    {
        array_map(function (Field $field) {
            return $field;
        }, $fields);
    }

    public function count()
    {
        return count($this->fields);
    }
}
