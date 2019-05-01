<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

use Illuminate\Contracts\Validation\Validator;
use Thinktomorrow\Chief\Fields\LocalizedFieldValidationRules;
use Thinktomorrow\Chief\Fields\Validators\FieldValidatorFactory;

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

    public function validation(...$arguments)
    {
        $this->values['validation'] = $arguments;

        return $this;
    }

    public function hasValidation(): bool
    {
        return (isset($this->values['validation']) && !empty($this->values['validation']));
    }

    public function validator(array $data): Validator
    {
        return app(FieldValidatorFactory::class)->create($this, $data);
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
        if (!$locale || !is_array($value)) {
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
