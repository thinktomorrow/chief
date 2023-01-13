<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation\Rules;

class FileMaxRule extends FileRule
{
    public function validate($attribute, array $values, $params, $validator): bool
    {
        foreach ($values as $value) {
            if ($value && false === $this->validateMax($attribute, $value, $params)) {
                $this->addCustomValidationMessage($attribute, $params, $validator);

                return false;
            }
        }

        return true;
    }

    public function validateMax($attribute, $value, $parameters)
    {
        if ($this->refersToExistingAsset($value)) {
            return $this->validateAssetMax($this->existingAsset($value), $parameters);
        }

        if (! $this->isValidFileInstance($value)) {
            $this->requireParameterCount(1, $parameters, 'max');

            return $this->getSlimImageSize($value) <= $parameters[0];
        }

        return parent::validateMax($attribute, $value, $parameters);
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    private function addCustomValidationMessage($attribute, $params, $validator): void
    {
        $validator->setCustomMessages([
            'file_max' => ':attribute is te groot en dient kleiner te zijn dan '.implode(',', $params).'Kb.',
        ]);
    }
}
