<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Thinktomorrow\Chief\Media\Application\MediaRequest;

class FileFieldDimensionsRule extends AbstractMediaFieldRule
{
    public function validate($attribute, $value, $params, $validator): bool
    {
        $value = $this->normalizePayload($value);

        foreach([MediaRequest::NEW, MediaRequest::REPLACE] as $type) {
            foreach($value[$type] as $file) {
                if($file && false !== $this->validateDimensions($attribute, $file, $params)) {
                    return true;
                }
            }
        }

        $validator->setCustomMessages([
            'filefield_dimensions' => 'De :attribute heeft niet de juiste afmetingen: ' . implode(', ', $params),
        ]);

        if(!isset($validator->customAttributes[$attribute])) {
            $validator->addCustomAttributes([
                $attribute => 'afbeelding',
            ]);
        }


        return false;
    }

    public function validateDimensions($attribute, $value, $parameters)
    {
        if($this->refersToExistingAsset($value)) {
            return $this->validateAssetDimensions($this->existingAsset($value), $parameters);
        }

        return parent::validateDimensions($attribute, $value, $parameters);
    }
}
