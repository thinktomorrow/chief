<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Common\Fields;

class Field
{
    /** @var FieldType */
    private $fieldType;

    protected $values = [];

    public function __construct(FieldType $fieldType, string $key)
    {
        $this->fieldType = $fieldType;

        $this->values['key'] = $this->values['column'] = $key;
        $this->values['type'] = $fieldType->get();
    }

    public function validation($rules = [], array $messages = [], array $attributes = [])
    {
        // Normalize rules: If no attribute is passed for the rule, we assume this to be the field key.
        if( !is_array($rules) || isset($rules[0])) {
            $rules = [$this->values['key'] => (is_array($rules) ? reset($rules) : $rules)];
        }

        $this->values['validation'] = ['rules' => $rules, 'messages' => $messages, 'customAttributes' => $attributes];

        return $this;
    }

    public function hasValidation(): bool
    {
        return (isset($this->values['validation']) && !empty($this->values['validation']));
    }

    public function ofType(...$type): bool
    {
        foreach($type as $_type) {
            if($this->fieldType->get() == $_type) return true;
        }

        return false;
    }

    public function __get($key)
    {
        if(isset($this->$key)) {
            return $this->$key;
        }

        if (!isset($this->values[$key])) {
            return null;
        }

        return $this->values[$key];
    }

    public function __call($name, $arguments)
    {
        // Without arguments we assume you want to retrieve a value property
        if (empty($arguments)) {
            return $this->__get($name);
        }

        if (!in_array($name, ['label', 'description', 'translatable', 'column'])) {
            throw new \InvalidArgumentException('Cannot set value by ['. $name .'].');
        }

        $this->values[$name] = $arguments[0];

        return $this;
    }
}
