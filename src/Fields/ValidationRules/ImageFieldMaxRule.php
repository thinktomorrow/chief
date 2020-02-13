<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Thinktomorrow\Chief\Media\Application\MediaRequest;

class ImageFieldMaxRule extends AbstractMediaFieldRule
{
    public function validate($attribute, $value, $params, $validator): bool
    {
        $value = $this->normalizePayload($value);

        foreach ([MediaRequest::NEW, MediaRequest::REPLACE] as $type) {
            foreach ($value[$type] as $file) {
                if ($file && false === $this->validateMax($attribute, $file, $params)) {

                    $this->addCustomValidationMessage($attribute, $params, $validator);

                    return false;
                }
            }
        }

        return true;
    }

    public function validateMax($attribute, $value, $parameters)
    {
        if ($this->refersToExistingAsset($value)) {
            return $this->validateAssetMax($this->existingAsset($value), $parameters);
        }

        $this->requireParameterCount(1, $parameters, 'max');

        return $this->getSize($attribute, $value) <= $parameters[0];
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
        $file = json_decode($value)->output;

        return $this->getBase64ImageSize($file->image) / 1024;
    }

    private function getBase64ImageSize($value)
    {
        return strlen(base64_decode($value));
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    private function addCustomValidationMessage($attribute, $params, $validator): void
    {
        $validator->setCustomMessages([
            'imagefield_max' => 'De :attribute is te groot en dient kleiner te zijn dan ' . implode(',', $params) . 'Kb.',
        ]);

        if (!isset($validator->customAttributes[$attribute])) {
            $validator->addCustomAttributes([
                $attribute => 'afbeelding',
            ]);
        }
    }
}
