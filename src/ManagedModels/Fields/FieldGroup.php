<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields;

use ArrayIterator;
use Illuminate\Support\Str;

class FieldGroup implements \ArrayAccess, \IteratorAggregate, \Countable
{
    private const EMPTY_ID = 'empty';

    private string $id;
    private array $fields;

    /**
     * Flag to indicate that this fieldGroup is set 'open' and allows to be added fields dynamically.
     * The 'close' method will stop the behaviour.
     */
    private bool $isOpen = false;

    final public function __construct(string $id, array $fields = [], bool $isOpen = false)
    {
        $this->id = $id;
        $this->validateFields($fields);

        $this->fields = $this->convertToKeyedArray($fields);
        $this->isOpen = $isOpen;
    }

    public static function make(iterable $generator): FieldGroup
    {
        $fields = new static(static::randomId());

        foreach ($generator as $field) {
            if (is_iterable($field)) {
                $fields = $fields->add(...$field);
            } else {
                $fields = $fields->add($field);
            }
        }

        return $fields;
    }

    public static function open(): FieldGroup
    {
        return new static(static::randomId(), [],true);
    }

    public static function close(): FieldGroup
    {
        return new static(static::randomId(), [], false);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isOpen(): bool
    {
        return $this->isOpen;
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

    public function find(string $key): ?Field
    {
        if (! isset($this->fields[$key])) {
            return null;
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

    public function map(callable $callback): FieldGroup
    {
        $keys = array_keys($this->fields);

        $items = array_map($callback, $this->fields, $keys);

        return new static($this->id, array_combine($keys, $items), $this->isOpen);
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

        foreach ($this->fields as $field) {
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

        return new static($this->id, $fields, $this->isOpen);
    }

//    public function render(): string
//    {
//        return array_reduce($this->fields, function (string $carry, Field $field) {
//            return $carry . $field->render();
//        }, '');
//    }

//    public function keyed($key): FieldGroup
//    {
//        $keys = (array) $key;
//
//        return new static(array_filter($this->fields, function (Field $field) use ($keys) {
//            return in_array($field->getKey(), $keys);
//        }));
//    }
//
//    public function tagged($tag): FieldGroup
//    {
//        return new static(array_filter($this->fields, function (Field $field) use ($tag) {
//            return $field->tagged($tag);
//        }));
//    }
//
//    public function notTagged($tag): FieldGroup
//    {
//        return new static(array_filter($this->fields, function (Field $field) use ($tag) {
//            return ! $field->tagged($tag);
//        }));
//    }
//
//    public function untagged(): FieldGroup
//    {
//        return new static(array_filter($this->fields, function (Field $field) {
//            return $field->untagged();
//        }));
//    }

    public function add(Field ...$fields): FieldGroup
    {
        return new static($this->id, array_merge($this->fields, $fields), $this->isOpen);
    }

    public function merge(FieldGroup $fieldGroup): FieldGroup
    {
        return new static($this->id, array_merge($this->fields, $fieldGroup->all()), $this->isOpen);
    }

//    /**
//     * @return static
//     */
//    public function remove($keys = null): self
//    {
//        // TODO: MAKE IMMUTABLE
//        if (! $keys) {
//            return $this;
//        }
//
//        if (is_string($keys)) {
//            $keys = func_get_args();
//        }
//
//        foreach ($this->fields as $k => $field) {
//            if (in_array($field->getKey(), $keys)) {
//                unset($this->fields[$k]);
//            }
//        }
//
//        return $this;
//    }

    public function offsetExists($offset)
    {
        return isset($this->fields[$offset]);
    }

    public function offsetGet($offset)
    {
        if (! isset($this->fields[$offset])) {
            throw new \RuntimeException('No field found by key ['.$offset.']');
        }

        return $this->fields[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (! $value instanceof Field) {
            throw new \InvalidArgumentException('Passed value must be of type '.Field::class);
        }

        $this->fields[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        // TODO: make immutable...
        unset($this->fields[$offset]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->fields);
    }

    public function count()
    {
        return count($this->fields);
    }

    private static function randomId(): string
    {
        return Str::random(8);
    }

    private function convertToKeyedArray(array $fields): array
    {
        $keyedFields = [];

        // @var Field
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
}
