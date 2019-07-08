<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
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
        $this->values['viewData'] = [];
        $this->values['type'] = $fieldType->get();
    }

    public function validation(...$arguments)
    {
        // If a Closure or Validator is passed, we do not want to pass it as an array.
        if (count($arguments) == 1 && !is_array($arguments)) {
            $this->values['validation'] = reset($arguments);

            return $this;
        }

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

    public function getFieldValue(Model $model, $locale = null)
    {
        // If string is passed, we use this to find the proper field
        if ($this->isTranslatable() && $locale) {
            return $model->getTranslationFor($this->column(), $locale);
        }

        return $model->{$this->column()};
    }

    /**
     * The view path to the full formgroup for this field.
     *
     * @param string|null $view
     * @param array $viewData
     * @return $this|mixed|null|string
     */
    public function view(string $view = null)
    {
        if ($view) {
            $this->values['view'] = $view;
            return $this;
        }

        return $this->__get('view') ?? 'chief::back._fields.formgroup';
    }

    public function viewData(array $viewData = [])
    {
        if ($viewData) {
            $this->values['viewData'] = $viewData;
            return $this;
        }

        return $this->__get('viewData');
    }

    /**
     * In case of the default formgroup rendering, there is also made use of
     * the form input element, which is targeted as a specific view as well
     *
     * @return string
     */
    public function formElementView(): string
    {
        return 'chief::back._fields.'.$this->fieldType->get();
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
