<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation\Rules;

class FileRequiredRule extends FileRule
{
    public function validate($attribute, array $values, $params, $validator): bool
    {
        foreach ($values as $value) {
            if (!is_null($value)) {
                return true;
            }
        }

        $validator->setCustomMessages([
            'file_required' => ':attribute is verplicht.',
        ]);

        return false;
    }
}
