<?php

namespace Thinktomorrow\Chief\Fields;

use ArrayIterator;
use Thinktomorrow\Chief\Fields\Types\Field;

class Fields implements \ArrayAccess, \IteratorAggregate
{
    /** @var array */
    private $fields;

    public function __construct(array $fields = [])
    {
        $this->validateFields($fields);

        $this->fields = $fields;
    }

    public function all(): array
    {
        return $this->fields;
    }

    public function filterBy($key, $value = null)
    {
        $fields = [];

        foreach($this->fields as $i => $field) {

            if(is_callable($key)) {
                if(true == $key($field)) {
                    $fields[] = $field;
                }

                continue;
            }

            // Reject from list if value does not match expected one
            if($value && $value == $field->$key) $fields[] = $field;

            // Reject from list if key returns null (key not present on field)
            elseif(!$value && !is_null($field->$key)) $fields[] = $field;
        }

        return new static($fields);
    }

    private function validateFields(array $fields)
    {
        array_map(function(Field $field){}, $fields);
    }

    public function add(Field $value)
    {
        $this->fields[] = $value;

        return $this;
    }

    public function offsetExists($offset)
    {
        return isset($this->fields[$offset]);
    }

    public function offsetGet($offset)
    {
        return (isset($this->fields[$offset]))
                ? $this->fields[$offset]
                : null;
    }

    public function offsetSet($offset, $value)
    {
        if( ! $value instanceof Field) {
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
}}