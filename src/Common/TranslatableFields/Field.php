<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Common\TranslatableFields;

class Field
{
    /** @var FieldType */
    private $fieldType;

    protected $values = [];

    public function __construct(FieldType $fieldType)
    {
        $this->fieldType = $fieldType;

        $this->values['type'] = $fieldType->get();
    }

    public function __get($key)
    {
        if (!isset($this->values[$key])) {
            return null;
        }

        return $this->values[$key];
    }

    public function __call($name, $arguments)
    {
        if (!in_array($name, ['label', 'description', 'type'])) {
            throw new \InvalidArgumentException('Cannot set value by ['. $name .'].');
        }

        // Without arguments we assume you want to retrieve a value property
        if(empty($arguments)) return $this->__get($name);

        $this->values[$name] = $arguments[0];

        return $this;
    }
}
