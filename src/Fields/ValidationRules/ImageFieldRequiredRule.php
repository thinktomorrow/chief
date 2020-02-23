<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Thinktomorrow\Chief\Media\Application\MediaRequest;

class ImageFieldRequiredRule extends AbstractMediaFieldRule
{
    public function validate($attribute, $values, $params, $validator): bool
    {
        foreach ($values as $key => $value) {
            if (!is_null($value)) {
                return true;
            }
        }

        $validator->setCustomMessages([
            'imagefield_required' => ':attribute is verplicht.',
        ]);

        if (!isset($validator->customAttributes[$attribute])) {
            $validator->addCustomAttributes([
                $attribute => 'afbeelding',
            ]);
        }


        return false;
    }
}
