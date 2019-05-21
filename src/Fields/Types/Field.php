<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

use Thinktomorrow\Chief\Fields\LocalizedFieldValidationRules;

class Field
{
    /** @var FieldType */
    private $fieldType;

    protected $values = [];

    public function __construct(FieldType $fieldType, string $key)
    {
        $this->fieldType = $fieldType;

        $this->values['key'] = $this->values['column'] = $this->values['name'] = $this->values['label'] = $key;
        $this->values['locales'] = [];
        $this->values['type'] = $fieldType->get();
    }

    public function validation($rules = [], array $messages = [], array $attributes = [])
    {
        $this->values['validation'] = ['rules' => $rules, 'messages' => $messages, 'customAttributes' => $attributes];

        return $this;
    }

    /**
     * @param array $data - request data payload
     * @return array|null
     */
    public function getValidation(array $data = [])
    {
        if (! $this->hasValidation()) {
            return null;
        }

        list('rules' => $rules, 'messages' => $messages, 'customAttributes' => $customAttributes) = $this->values['validation'];

        // Normalize rules: If no attribute is passed for the rule, we use the field name.
        if (!is_array($rules) || isset($rules[0])) {
            $rules = [$this->values['name'] => (is_array($rules) ? reset($rules) : $rules)];

            if ($this->isTranslatable()) {
                $rules = (new LocalizedFieldValidationRules($this->locales))
                            ->influenceByPayload($data)
                            ->rules($rules);
            }
        }

        return ['rules' => $rules, 'messages' => $messages, 'customAttributes' => $customAttributes];
    }

    public function hasValidation(): bool
    {
        return (isset($this->values['validation']) && !empty($this->values['validation']));
    }

    public function translatable(array $locales = [])
    {
        $this->values['locales'] = $locales;
        return $this;
    }

    public function isTranslatable(): bool
    {
        return count($this->values['locales']) > 0;
    }

    public function ofType(...$type): bool
    {
        foreach ($type as $_type) {
            if ($this->fieldType->get() == $_type) {
                return true;
            }
        }

        return false;
    }

    public static function translateValue($value, $locale = null)
    {
        if(!$locale || !is_array($value)) {
            return $value;
        }

        if ($locale && isset($value[$locale])) {
            return $value[$locale];
        }

        return $value;
    }

    public function __get($key)
    {
        if (isset($this->$key)) {
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

        if (!in_array($name, ['label', 'key', 'description', 'column', 'name', 'prepend', 'append'])) {
            throw new \InvalidArgumentException('Cannot set value by ['. $name .'].');
        }

        $this->values[$name] = $arguments[0];

        return $this;
    }
}
