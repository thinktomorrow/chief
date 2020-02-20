<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Thinktomorrow\Chief\Media\Application\MediaRequest;

class FileFieldDimensionsRule extends AbstractMediaFieldRule
{
    public function validate($attribute, $values, $params, $validator): bool
    {
        foreach ($values as $key => $value) {
            if ($value && false === $this->validateDimensions($attribute, $value, $params)) {
                $this->addCustomValidationMessage($attribute, $params, $validator);

                return false;
            }
        }

        return true;
    }

    public function validateDimensions($attribute, $value, $parameters)
    {
        if ($this->refersToExistingAsset($value)) {
            return $this->validateAssetDimensions($this->existingAsset($value), $parameters);
        }

        return parent::validateDimensions($attribute, $value, $parameters);
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    private function addCustomValidationMessage($attribute, $params, $validator): void
    {
        $validator->setCustomMessages([
            'filefield_dimensions' => 'De :attribute heeft niet de juiste afmetingen: ' . implode(', ', $params),
        ]);

        if (!isset($validator->customAttributes[$attribute])) {
            $validator->addCustomAttributes([
                $attribute => 'afbeelding',
            ]);
        }
    }
}
