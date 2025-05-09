<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation\Rules;

use Thinktomorrow\Chief\Sites\ChiefSites;

class FallbackLocaleRequiredRule
{
    const RULE = 'requiredFallbackLocale';

    public function validate($attribute, $value, $params, $validator): bool
    {
        $fallbackLocale = ChiefSites::primaryLocale();

        $validator->setCustomMessages([
            'required_fallback_locale' => 'Voor :attribute is de default taal verplicht.',
        ]);

        if (str_ends_with($attribute, '.'.$fallbackLocale)) {

            // TODO: for asset field this should be custom made because here the value is the entire file upload payload (including new uploads, attachments of existing assets, deletions, ...)
            if (is_array($value)) {
                return true;
            } // TEMP false passing of validation

            return is_null($value) ? false : (bool) trim($value);
        }

        return true;
    }
}
