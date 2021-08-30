<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields;

use ArrayIterator;
use Illuminate\Support\Collection;

class Fields implements \ArrayAccess, \IteratorAggregate, \Countable
{
    private Collection $fieldGroups;

    final public function __construct(array $fieldGroups = [])
    {
        $fieldGroups = $this->giveNomadFieldsAFieldGroup($fieldGroups);
        $this->validateFieldGroups($fieldGroups);

        $fieldGroups = $this->removeEmptyFieldGroups($fieldGroups);
        $this->fieldGroups = collect($fieldGroups);
    }

    /**
     * @return static
     */
    public static function make(iterable $generator): self
    {
        // TODO: convert to fieldgroup if not present
        $fields = new static();

        foreach ($generator as $field) {
            if (is_iterable($field)) {
                $fields = $fields->add(...$field);
            } else {
                $fields = $fields->add($field);
            }
        }

        return $fields;
    }

    public function all(): Collection
    {
        return $this->fieldGroups;
    }

    public function allFields(): Collection
    {
        $fields = collect();

        $this->fieldGroups->each(function($fieldGroup) use(&$fields) {
            $fields = $fields->merge($fieldGroup->all());
        });

        return $fields;
    }

    public function first(): ?Field
    {
        if ($this->fieldGroups->isEmpty()) {
            return null;
        }

        return $this->fieldGroups->first()->first();
    }

    public function find(string $key): Field
    {
        foreach($this->fieldGroups as $fieldGroup) {
            if($field = $fieldGroup->find($key)) {
                return $field;
            }
        }

        throw new \InvalidArgumentException('No field found by key ' . $key);
    }

    public function any(): bool
    {
        return !$this->fieldGroups->isEmpty();
    }

    public function isEmpty(): bool
    {
        return $this->fieldGroups->isEmpty();
    }

    public function keys(): array
    {
        $fieldKeys = [];

        $this->fieldGroups->each(function($fieldGroup) use(&$fieldKeys) {
            $fieldKeys = array_merge($fieldKeys, $fieldGroup->keys());
        });

        return $fieldKeys;
    }

    private function map(callable $callback): Fields
    {
        return new static($this->fieldGroups->map($callback)->all());
    }

    /**
     * @param \Closure|string $key
     * @param null|mixed $value
     *
     * @return static
     */
    public function filterBy($key, $value = null): self
    {
        return new static($this->fieldGroups->map(function($fieldGroup) use($key,$value){
            return $fieldGroup->filterBy($key,$value);
        })->all());
    }

    public function model($model): self
    {
        return $this->map(function ($fieldGroup) use ($model) {
            return $fieldGroup->map(function($field) use($model) {
                return $field->model($model);
            });
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

        foreach ($this->fieldGroups as $fieldGroup) {
            if (! isset($fields[$fieldGroup->componentKey()])) {
                $fields[$fieldGroup->componentKey()] = new static();
            }

            $fields[$fieldGroup->componentKey()] = $fields[$fieldGroup->componentKey()]->add($fieldGroup);
        }

        return $fields;
    }

    public function render(): string
    {
        return $this->fieldGroups->reduce(function (string $carry, Field $field) {
            return $carry . $field->render();
        }, '');
    }

    public function keyed($key): Fields
    {
        $keys = (array) $key;

        return $this->filterBy(function (Field $field) use ($keys) {
            return in_array($field->getKey(), $keys);
        });
    }

    public function tagged($tag): Fields
    {
        return $this->filterBy(function (Field $field) use ($tag) {
            return $field->tagged($tag);
        });
    }

    public function notTagged($tag): Fields
    {
        return $this->filterBy(function (Field $field) use ($tag) {
            return ! $field->tagged($tag);
        });
    }

    public function untagged(): Fields
    {
        return $this->filterBy(function (Field $field) {
            return $field->untagged();
        });
    }

    public function add(FieldGroup ...$fieldGroups): Fields
    {
        return new static($this->fieldGroups->merge($fieldGroups)->all());
    }

    public function merge(Fields $fields): Fields
    {
        $purgedFieldGroups = $this->remove($fields->keys())->all();

        return new static($purgedFieldGroups->merge($fields->all())->all());
    }

    /** @return static */
    public function remove($keys = null): self
    {
        return $this->filterBy(function (Field $field) use ($keys) {
            return !in_array($field->getKey(), $keys);
        });
    }

    public function offsetExists($offset)
    {
        return isset($this->fieldGroups[$offset]);
    }

    public function offsetGet($offset)
    {
        if (! isset($this->fieldGroups[$offset])) {
            throw new \RuntimeException('No fieldgroup found by key [' . $offset . ']');
        }

        return $this->fieldGroups[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (! $value instanceof FieldGroup) {
            throw new \InvalidArgumentException('Passed value must be of type ' . FieldGroup::class);
        }

        $this->fieldGroups[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->fieldGroups[$offset]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->fieldGroups);
    }

    private function giveNomadFieldsAFieldGroup(array $fieldGroups): array
    {
        $result = [];

        foreach($fieldGroups as $fieldGroup) {
            if($fieldGroup instanceof FieldGroup) {
                $result[] = $fieldGroup;
            } elseif($fieldGroup instanceof Field) {
                $result[] = new FieldGroup([$fieldGroup]);
            } else {
                throw new \InvalidArgumentException('Only FieldGroup of Field instances should be passed.');
            }
        }

        return $result;
    }

    private function validateFieldGroups(array $fieldGroups): void
    {
        array_map(fn(FieldGroup $fieldGroup) => $fieldGroup, $fieldGroups);
    }

    public function count()
    {
        return count($this->fieldGroups);
    }

    private function removeEmptyFieldGroups(array $fieldGroups): array
    {
        foreach($fieldGroups as $k => $fieldGroup) {
            if($fieldGroup->isEmpty()) {
                unset($fieldGroups[$k]);
            }
        }

        return array_values($fieldGroups);
    }
}
