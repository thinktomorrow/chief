<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\ValidationRules;

class ImageFieldMinRule extends AbstractMediaFieldRule
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

        $this->requireParameterCount(1, $parameters, 'min');

        return $this->getSize($attribute, $value) >= $parameters[0];
    }

    /**
     * A method required by the validateMax method
     *
     * @param $attribute
     * @param $value
     * @return float|int
     */
    protected function getSize($attribute, $value)
    {
        $inputData = json_decode($value)->input;

        // size in Kilobytes (slim component already provides a size that, due to the way slim stored this,
        //  we need reduce to kilobytes by dividing 1000 instead of the expected 1024.
        return $inputData->size / 1000;
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    private function addCustomValidationMessage($attribute, $params, $validator): void
    {
        $validator->setCustomMessages([
            'imagefield_min' => ':attribute is te klein en dient groter te zijn dan ' . implode(',', $params) . 'Kb.',
        ]);

        if (!isset($validator->customAttributes[$attribute])) {
            $validator->addCustomAttributes([
                $attribute => 'afbeelding',
            ]);
        }
    }
}
