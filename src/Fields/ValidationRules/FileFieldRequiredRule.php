<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Thinktomorrow\Chief\Media\Application\MediaRequest;

class FileFieldRequiredRule extends AbstractMediaFieldRule
{
    public function validate($attribute, $value, $params, $validator): bool
    {
        $value = $this->normalizePayloadIncludingExistingMedia($value);

        foreach ([MediaRequest::NEW, MediaRequest::REPLACE] as $type) {
            if (is_array($value[$type]) && !empty($value[$type])) {
                return true;
            }
        }

        $validator->setCustomMessages([
            'filefield_required' => 'De :attribute is verplicht.',
        ]);

        if (!isset($validator->customAttributes[$attribute])) {
            $validator->addCustomAttributes([
                $attribute => 'afbeelding',
            ]);
        }

        return false;
    }
}
