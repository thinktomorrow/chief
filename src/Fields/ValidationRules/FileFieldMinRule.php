<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Thinktomorrow\Chief\Media\Application\MediaRequest;

class FileFieldMinRule extends AbstractMediaFieldRule
{
    public function validate($attribute, $value, $params, $validator): bool
    {
        $value = $this->normalizePayload($value);

        foreach ([MediaRequest::NEW, MediaRequest::REPLACE] as $type) {
            foreach ($value[$type] as $file) {
                if ($file && false === $this->validateMin($attribute, $file, $params)) {
                    $this->addCustomValidationMessage($attribute, $params, $validator);

                    return false;
                }
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
