<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

class FallbackLocaleRequiredRule
{
    const RULE = 'requiredFallbackLocale';

    public function validate($attribute, $value, $params, $validator): bool
    {
        $validator->setCustomMessages([
            'required_fallback_locale' => 'Voor :attribute is minstens de default taal verplicht in te vullen, aub.',
        ]);

        $fallbackLocale = config('app.fallback_locale');

        if (false !== strpos($attribute, 'trans.'.$fallbackLocale.'.')) {
            return is_null($value) ? false : (bool) trim($value);
        }

        return true;
    }
}
