<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation\Rules;

class FileMinRule extends FileRule
{
    public function validate($attribute, array $values, $params, $validator): bool
    {
        foreach ($values as $value) {
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

        if (!$this->isValidFileInstance($value)) {
            $this->requireParameterCount(1, $parameters, 'min');
            return $this->getSlimImageSize($value) >= $parameters[0];
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
            'file_min' => ':attribute is te klein en dient groter te zijn dan ' . implode(',', $params) . 'Kb.',
        ]);
    }
}
