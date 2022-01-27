<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Closure;

trait HasValue
{
    protected $value;

    /**
     * Flag to indicate internally that a value has been explicitly set (via value()).
     * This makes it possible to also set null value as well.
     */
    protected bool $valueGiven = false;

    public function value(mixed $value): static
    {
        $this->value = $value;
        $this->valueGiven = true;

        return $this;
    }

    /** Value of active form request */
    public function getActiveValue(?string $locale = null)
    {
        return old($this->getId($locale), $this->getValue($locale));
    }

    public function getValue(?string $locale = null): mixed
    {
        if (! $this->valueGiven) {
            if (! $this->getModel()) {
                return $this->getDefault($locale);
            }

            return $this->defaultEloquentValueResolver()($this->getModel(), $locale);
        }

        if (is_callable($this->value)) {
            return call_user_func_array($this->value, [$this->getModel(), $locale, $this]);
        }

        // Localized value
        if ($locale && is_array($this->value) && array_key_exists($locale, $this->value)) {
            return $this->value[$locale];
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
        return function ($model, $locale = null) {
            if ($locale && $this->hasLocales()) {
                if (method_exists($model, 'isDynamic') && $model->isDynamic($this->getColumnName())) {
                    return $model->dynamic($this->getColumnName(), $locale, $this->getDefault($locale));
                }

                // Astrotomic translatable
                return $model->{$this->getColumnName().':'.$locale};
            }

            return $model->{$this->getColumnName()} ?? $this->getDefault($locale);
        };
    }
}
