<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Symfony\Component\HttpFoundation\File\File;
use Thinktomorrow\Chief\Media\Application\MediaRequest;

class FileFieldMaxRule extends AbstractMediaFieldRule
{
    public function validate($attribute, $values, $params, $validator): bool
    {
        foreach ($values as $key => $value) {
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

        return parent::validateMax($attribute, $value, $parameters);
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
        if (!$value instanceof File) {
            throw new \InvalidArgumentException('Value is expected to be of type ' . File::class);
        }

        return $value->getSize() / 1024;
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    private function addCustomValidationMessage($attribute, $params, $validator): void
    {
        $validator->setCustomMessages([
            'filefield_max' => ':attribute is te groot en dient kleiner te zijn dan ' . implode(',', $params) . 'Kb.',
        ]);

        if (!isset($validator->customAttributes[$attribute])) {
            $validator->addCustomAttributes([
                $attribute => 'afbeelding',
            ]);
        }
    }
}
