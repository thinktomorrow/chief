<?php declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fields\Validators\FieldValidatorFactory;

class Field
{
    /** @var FieldType */
    private $fieldType;

    protected $values = [];

    /** @var callable */
    protected $valueResolver;

    /**
     * Fixed default value.
     * This default value is trumped by the existing model value.
     * @var mixed
     */
    protected $default = null;

    final public function __construct(FieldType $fieldType, string $key)
    {
        $this->fieldType = $fieldType;

        $this->values['key'] = $this->values['column'] = $this->values['name'] = $this->values['label'] = $key;
        $this->values['locales'] = [];
        $this->values['viewData'] = [];
        $this->values['type'] = $fieldType->get();

        $this->valueResolver($this->defaultValueResolver());
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

    public function required(): bool
    {
        if (!$this->hasValidation()) {
            return false;
        }

        foreach ($this->values['validation'] as $rule) {
            if (false !== strpos($rule, 'required')) {
                return true;
            }
        };

        return false;
    }

    public function optional(): bool
    {
        return ! $this->required();
    }

    public function name(string $name = null)
    {
        if (!is_null($name)) {
            $this->values['name'] = $name;

            return $this;
        }

        return $this->values['name'] ?? $this->key();
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

    public function translateName($locale)
    {
        $name = $this->name();

        if (strpos($name, ':locale')) {
            return preg_replace('#(:locale)#', $locale, $name);
        }

        return 'trans['.$locale.']['.$name.']';
    }

    public static function translateValue($values, $locale = null)
    {
        if (!$locale || !is_array($values)) {
            return $values;
        }

        if ($locale && isset($values[$locale])) {
            return $values[$locale];
        }

        return $values;
    }

    public function getFieldValue(Model $model, $locale = null)
    {
        $value = call_user_func_array($this->valueResolver, [$model, $locale]);

        if (is_null($value)) {
            return $this->default;
        }

        return $value;
    }

    public function valueResolver(callable $callable)
    {
        $this->valueResolver = $callable;

        return $this;
    }

    private function defaultValueResolver(): callable
    {
        return function (Model $model, $locale) {
            if ($this->isTranslatable() && $locale) {
                return $model->getTranslationFor($this->column(), $locale);
            }

            return $model->{$this->column()};
        };
    }

    public function default($default)
    {
        $this->default = $default;

        return $this;
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
