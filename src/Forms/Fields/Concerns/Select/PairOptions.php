<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns\Select;

class PairOptions
{
    public static function toPairs(array $options): array
    {
        return static::enforceKeyValuePairs($options);
    }

    /**
     * Convert non-associative array to associative one.
     * If you want to force a non-assoc. array, you can use a Closure.
     * If it's a nested array which is used by the grouping of the Multiselect.
     */
    private static function enforceKeyValuePairs(array $options): array
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

    public static function areOptionsGrouped(array $options): bool
    {
        $firstGroup = reset($options);

        return is_array($firstGroup)
            && array_key_exists('label', $firstGroup) // Can be null which is not a crime
            && isset($firstGroup['options'])
            && is_array($firstGroup['options']);
    }

    private static function enforcePairs(array $options): array
    {
        if (array_is_list($options) && ! is_array($options[0])) {
            $options = array_combine($options, $options);
        }

        return collect($options)->map(function ($label, $value) use ($options) {
            // Passed option can already be a paired item as ["key" => "one", "value" => "een"]
            if (is_array($label)) {

                if (! isset($label['value'], $label['label'])) {
                    dd($label, $options);
                    throw new InvalidOptionsForMultiSelect('Invalid MultiSelect option passed: ['.key($label).':'.reset($label).']');
                }

                return $label;
            }

            return ['value' => $value, 'label' => $label];
        })->values()->all();
    }

    public static function toMultiSelectPairs(array $options): array
    {
        return static::convertOptionsToChoices(static::enforceKeyValuePairs($options));
    }

    /**
     * Set the right syntax as expected by choices.js
     */
    public static function convertOptionsToChoices(array $options): array
    {
        if (static::areOptionsGrouped($options)) {
            foreach ($options as $k => $optionGroup) {
                $options[$k]['choices'] = $optionGroup['options'];
                unset($options[$k]['options']);
            }
        }

        return $options;
    }
}
