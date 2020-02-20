<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Thinktomorrow\Chief\Media\Application\MediaRequest;

class FileFieldMinRule extends AbstractMediaFieldRule
{
    public function validate($attribute, $values, $params, $validator): bool
    {
        foreach ($values as $key => $value) {
            if ($value && false === $this->validateMin($attribute, $value, $params)) {
                $this->addCustomValidationMessage($attribute, $params, $validator);

                return false;
            }
        }

        return true;
    }

    public function validateMin($attribute, $value, $parameters)
    {
        if ($this->refersToExistingAsset($value)) {
            return $this->validateAssetMin($this->existingAsset($value), $parameters);
        }

        return parent::validateMin($attribute, $value, $parameters);
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    private function addCustomValidationMessage($attribute, $params, $validator): void
    {
        $validator->setCustomMessages([
            'filefield_min' => 'De :attribute is te klein en dient groter te zijn dan ' . implode(',', $params) . 'Kb.',
        ]);

        if (!isset($validator->customAttributes[$attribute])) {
            $validator->addCustomAttributes([
                $attribute => 'afbeelding',
            ]);
        }
    }
}
