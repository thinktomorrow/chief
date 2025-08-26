<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Relations\Relation;

trait HasValue
{
    use HasValueFallback;
    use HasValuePreparation;

    protected mixed $value;

    protected mixed $originalValue;

    /**
     * Flag to indicate internally that a value has been explicitly set (via value()).
     * This makes it possible to purposely set null as a value.
     */
    protected bool $valueGiven = false;

    protected bool $originalValueGiven = false;

    public function value(mixed $value): static
    {
        $this->value = $value;
        $this->valueGiven = true;

        if (isset($this->locale)) {
            $this->valueGivenForLocale = $this->locale;
        }

        return $this;
    }

    /** Value of active form request */
    public function getActiveValue(?string $locale = null)
    {
        return old($this->getId($locale), $this->getValue($locale));
    }

    public function getValue(?string $locale = null): mixed
    {
        $value = $this->getRawValue($locale);

        return $this->hasPrepareValue() ? $this->getPrepareValue()($value) : $value;
    }

    /**
     * When value has been altered or mapped, this method
     * can be used to retrieve the original value.
     */
    public function getOriginalValue(?string $locale = null): mixed
    {
        if ($this->originalValueGiven) {
            return $this->originalValue;
        }

        return $this->getRawValue($locale);
    }

    public function setOriginalValue(mixed $value): static
    {
        $this->originalValue = $value;
        $this->originalValueGiven = true;

        return $this;
    }

    public function getValueOrFallback(?string $locale = null): mixed
    {
        $currentFallbackValue = $this->useValueFallback;

        $this->useValueFallback();

        $value = $this->getValue($locale);

        $this->useValueFallback($currentFallbackValue);

        return $value;
    }

    private function getRawValue(?string $locale = null): mixed
    {
        if (! $this->valueGiven) {

            if (! $this->getModel()) {
                return $this->getDefault($locale);
            }

            if (is_array($this->getModel())) {
                return data_get($this->getModel(), $this->getColumnName(), $this->getDefault($locale));
            }

            return $this->defaultEloquentValueResolver()($this->getModel(), $locale, $this->useValueFallback);
        }

        // Check if it is a closure
        if ($this->value instanceof Closure) {
            return call_user_func_array($this->value, [$this->getModel(), $locale, $this]);
        }

        // Localized value
        if ($locale && is_array($this->value)) {
            return array_key_exists($locale, $this->value)
                ? $this->value[$locale]
                : $this->getDefault($locale);
        }

        // Relationships are retrieved as collections - if they
        // are empty, we can return the default instead
        if (is_countable($this->value) && count($this->value) == 0) {
            $default = $this->getDefault($locale);

            return (is_countable($default) && count($default) > 0) ? $default : $this->value;
        }

        return $this->value;
    }

    /**
     * Default value retrieval.
     *
     * This will be sufficient for most cases. First we try to retrieve
     * If the model has no c the property, this value will be used.
     * Otherwise the passed value will be used instead.
     */
    private function defaultEloquentValueResolver(): Closure
    {
        return function ($model, ?string $locale = null, bool $withLocaleFallback = false) {
            if ($locale && $this->hasLocales()) {

                if (method_exists($model, 'isDynamic') && $model->isDynamic($this->getColumnName())) {

                    if ($withLocaleFallback) {
                        return $model->localizedDynamic($this->getColumnName(), $locale);
                    }

                    return $model->dynamic($this->getColumnName(), $locale, $this->getDefault($locale));
                }

                // Astrotomic translatable
                return $model->{$this->getColumnName().':'.$locale};
            }

            // Dotted syntax as support for array casts. We can fetch nested values as: days.0.am, days.0.pm, ...
            if (str_contains($this->getColumnName(), '.')) {
                $column = substr($this->getColumnName(), 0, strpos($this->getColumnName(), '.'));
                $key = substr($this->getColumnName(), strpos($this->getColumnName(), '.') + 1);

                if (is_array($model->{$column})) {
                    return data_get($model->{$column}, trim($key, '.'));
                }
            }

            // Only relation methods can be called as a property. Other methods are treated as regular methods.
            if (method_exists($model, $this->getColumnName())) {

                $value = $model->{$this->getColumnName()}();

                if ($value instanceof Relation) {
                    $value = $model->{$this->getColumnName()};
                }
            } else {
                // Default Eloquent value retrieval as a property (or relation)
                $value = $model->{$this->getColumnName()};
            }

            // Relationships are retrieved as collections - if they
            // are empty, we can return the default instead
            if (is_countable($value) && count($value) == 0) {
                $default = $this->getDefault($locale);

                return (is_countable($default) && count($default) > 0) ? $default : $value;
            }

            return $value ?? $this->getDefault($locale);
        };
    }
}
