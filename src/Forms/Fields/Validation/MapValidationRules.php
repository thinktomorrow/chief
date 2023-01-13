<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation;

class MapValidationRules
{
    public function handle(array $rules, array $replacements): array
    {
        foreach ($rules as $k => $rule) {
            $params = '';

            // Split up the rule and any parameters
            if (false !== strpos($rule, ':')) {
                list($rule, $params) = explode(':', $rule);
            }

            if (isset($replacements[$rule])) {
                $rules[$k] = $replacements[$rule].($params ? ':'.$params : '');
            }
        }

        return $rules;
    }
}
