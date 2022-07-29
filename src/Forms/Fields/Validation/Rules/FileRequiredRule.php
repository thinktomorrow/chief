<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation\Rules;

class FileRequiredRule extends FileRule
{
    public function validate($attribute, ?array $values, $params, $validator): bool
    {
        $validator->setCustomMessages([
            'file_required' => ':attribute is verplicht.',
        ]);

        if(is_null($values)) return false;

        foreach ($values as $value) {
            if (! is_null($value)) {
                return true;
            }
        }

        return false;
    }
}
