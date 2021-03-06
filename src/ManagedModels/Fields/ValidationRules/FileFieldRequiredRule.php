<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\ValidationRules;

class FileFieldRequiredRule extends AbstractMediaFieldRule
{
    public function validate($attribute, array $values, $params, $validator): bool
    {
        foreach ($values as $value) {
            if (! is_null($value)) {
                return true;
            }
        }

        $validator->setCustomMessages([
            'filefield_required' => ':attribute is verplicht.',
        ]);

        if (! isset($validator->customAttributes[$attribute])) {
            $validator->addCustomAttributes([
                $attribute => 'afbeelding',
            ]);
        }

        return false;
    }
}
