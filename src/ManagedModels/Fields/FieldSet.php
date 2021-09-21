<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields;

use ArrayIterator;
use Illuminate\Support\Str;

class FieldSet implements \ArrayAccess, \IteratorAggregate, \Countable
{
    private string $id;
    private array $fields;
    private array $data;

    final public function __construct(string $id, array $fields = [], array $data = [])
    {
        $this->id = $id;
        $this->validateFields($fields);

        $this->fields = $this->convertToKeyedArray($fields);
        $this->data = $data;
    }

    public static function make(iterable $generator): FieldSet
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

    public static function open(?string $id = null): FieldSet
    {
        // We set column only when an explicit id has been set.
        return new static($id ?: static::randomId(), [], ['is_open' => true]);
    }

    public static function close(): FieldSet
    {
        return new static(static::randomId(), [], ['is_open' => false]);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isOpen(): bool
    {
        return $this->data['is_open'] ?? false;
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

    public function map(callable $callback): FieldSet
    {
        $keys = array_keys($this->fields);
        $items = array_map($callback, $this->fields, $keys);

        return new static($this->id, array_combine($keys, $items), $this->data);
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

        return new static($this->id, $fields, $this->data);
    }

    public function add(Field ...$fields): FieldSet
    {
        return new static($this->id, array_merge($this->fields, $fields), $this->data);
    }

    public function merge(FieldSet $fieldSet): FieldSet
    {
        return new static($this->id, array_merge($this->fields, $fieldSet->all()), $this->data);
    }

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

    public function title(string $title): FieldSet
    {
        return new static(
            $this->id,
            $this->fields,
            array_merge($this->data, ['title' => $title]),
        );
    }

    public function getTitle(): string
    {
        return $this->data['title'] ?? '';
    }

    public function description(string $description): FieldSet
    {
        return new static(
            $this->id,
            $this->fields,
            array_merge($this->data, ['description' => $description]),
        );
    }

    public function getDescription(): string
    {
        return $this->data['description'] ?? '';
    }

    public function class(string $class): self
    {
        $this->data['class'] = $class;

        return $this;
    }

    public function getClass(): string
    {
        return $this->data['class'] ?? '';
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
