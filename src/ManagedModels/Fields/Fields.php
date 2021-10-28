<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields;

use ArrayIterator;
use Illuminate\Support\Collection;

class Fields implements \ArrayAccess, \IteratorAggregate, \Countable
{
    private Collection $items;

    final private function __construct(array $items = [])
    {
        $this->validateFields($items);
        $this->items = collect($items)->mapWithKeys(function ($item) {
            return [$item->getKey() => $item];
        });
    }

    public static function make(iterable $generator = []): self
    {
        $fields = new static();

        foreach ($generator as $field) {
            if ($field instanceof self) {
                $fields = $fields->add(...$field->values());
            } elseif (is_iterable($field)) {
                trap($field);
                $fields = $fields->add(...$field);
            } else {
                $fields = $fields->add($field);
            }
        }

        return $fields;
    }

    public function first(): ?Field
    {
        return $this->items->first();
    }

    public function find(string $key): Field
    {
        if (! isset($this->items[$key])) {
            throw new \InvalidArgumentException('No field found by key '.$key);
        }

        return $this->items[$key];
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
        return $this->items->keys()->all();
    }

    public function map(callable $callback): self
    {
        return new static($this->items->map($callback)->all());
    }

    /**
     * @param \Closure|string $key
     * @param null|mixed      $value
     *
     * @return static
     */
    public function filterBy($key, $value = null): self
    {
        $fields = [];

        foreach ($this->items as $field) {
            if ($key instanceof \Closure) {
                if (true == $key($field)) {
                    $fields[] = $field;
                }

                continue;
            }

            $method = 'get'.ucfirst($key);

            // Reject from list if value does not match expected one
            if ($value && $field->{$method}() == $value) {
                $fields[] = $field;
            } // Reject from list if key returns null (key not present on field)
            elseif (! $value && ! is_null($field->{$method}())) {
                $fields[] = $field;
            }
        }

        return new static($fields);
    }

    public function add(Field ...$fields): self
    {
        return $this->merge(new static($fields));
    }

    public function merge(self $other): self
    {
        return new static($this->items->merge($other->all())->all());
    }

    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        if (! isset($this->items[$offset])) {
            throw new \RuntimeException('No field found by key ['.$offset.']');
        }

        return $this->items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (! $value instanceof Field) {
            throw new \InvalidArgumentException('Passed value must be of type '.Field::class);
        }

        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        // TODO: make immutable...
        unset($this->items[$offset]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items->all());
    }

    public function count()
    {
        return count($this->items);
    }

    public function all(): Collection
    {
        return $this->items;
    }

    public function model($model): self
    {
        return $this->map(function ($field) use ($model) {
            return $field->model($model);
        });
    }

    public function keyed($key): self
    {
        $keys = (array) $key;

        return $this->filterBy(function (Field $field) use ($keys) {
            return in_array($field->getKey(), $keys);
        });
    }

    public function tagged($tag): self
    {
        if (is_string($tag) && 'untagged' === $tag) {
            return $this->untagged();
        }

        return $this->filterBy(function (Field $field) use ($tag) {
            return $field->tagged($tag);
        });
    }

    public function notTagged($tag): self
    {
        return $this->filterBy(function (Field $field) use ($tag) {
            return ! $field->tagged($tag);
        });
    }

    public function untagged(): self
    {
        return $this->filterBy(function (Field $field) {
            return $field->untagged();
        });
    }

    public function remove($keys = null): self
    {
        return $this->filterBy(function (Field $field) use ($keys) {
            return ! in_array($field->getKey(), $keys);
        });
    }

    private function values(): array
    {
        return $this->items->values()->all();
    }

    private function validateFields(array $fields): void
    {
        array_map(function (Field $field) {
            return $field;
        }, $fields);
    }
}
