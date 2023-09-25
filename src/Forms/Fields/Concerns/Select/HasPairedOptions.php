<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns\Select;

use Closure;

trait HasPairedOptions
{
    protected array|Closure $options = [];

    private bool $sanitizeOptions = true;

    public function rawOptions(array|Closure $options): static
    {
        return $this->options($options, false);
    }

    public function options(array|Closure $options, bool $sanitize = true): static
    {
        $this->sanitizeOptions = $sanitize;

        $this->options = $sanitize ? static::enforceKeyValuePairs($options) : $options;

        return $this;
    }

    /**
     * Convert non-associative array to associative one.
     * If you want to force an non-assoc. array, you can use a Closure.
     * If it's a nested array which is used by the grouping of the Multiselect.
     */
    private static function enforceKeyValuePairs(array|Closure $options): array|Closure
    {
        // Empty array
        if (is_array($options) && empty($options)) {
            return $options;
        }

        // Closure
        if (is_callable($options)) {
            return $options;
        }

        if (static::areOptionsGrouped($options)) {
            foreach ($options as $k => $optionGroup) {
                $options[$k]['options'] = static::enforcePairs($optionGroup['options']);
            }

            return $options;
        }

        return static::enforcePairs($options);
    }

    private static function areOptionsGrouped(array $options): bool
    {
        $firstGroup = reset($options);

        return is_array($firstGroup)
            && isset($firstGroup['label'], $firstGroup['options'])
            && is_array($firstGroup['options']);
    }

    private static function enforcePairs(array $options): array
    {
        if (array_is_list($options) && ! is_array($options[0])) {
            $options = array_combine($options, $options);
        }

        return collect($options)->map(function ($label, $value) {

            // Passed option can already be a paired item as ["key" => "one", "value" => "een"]
            if (is_array($label)) {

                if (! isset($label['value'], $label['label'])) {
                    throw new InvalidOptionsForMultiSelect('Invalid MultiSelect option passed: [' . key($label) . ':' . reset($label) . ']');
                }

                return $label;
            }

            return ['value' => $value, 'label' => $label];
        })->values()->all();
    }

    public function getOptions(?string $locale = null): array
    {
        $options = $this->options;

        if (is_callable($options)) {
            $options = call_user_func_array($options, [$this, $this->getModel(), $locale]);

            if ($this->sanitizeOptions) {
                $options = static::enforceKeyValuePairs($options);
            }
        }

        return static::convertOptionsToChoices($options);
    }

    /**
     * Set the right syntax as expected by choices.js
     */
    private static function convertOptionsToChoices(array $options): array
    {
        if (static::areOptionsGrouped($options)) {
            foreach ($options as $k => $optionGroup) {
                $options[$k]['choices'] = $optionGroup['options'];
                unset($options[$k]['options']);
            }
        }

        return $options;
    }

    // ASYNC???
    // DYNAMIC
    // ONLY ON LOADING OF FIELD -> LIVEWIRE

    // id, value, label, customProperties [description, random, ...], groupValue, keyCode, disabled, selected

    // GROUP
    //label, id, choices, disabled,
}
