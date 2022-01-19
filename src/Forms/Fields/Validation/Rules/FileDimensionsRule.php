<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation\Rules;

class FileDimensionsRule extends FileRule
{
    public function validate($attribute, array $values, $params, $validator): bool
    {
        foreach ($values as $value) {
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

        if (!$this->isValidFileInstance($value)) {
            return $this->validateSlimOutputDimensions($value, $parameters);
        }

        return parent::validateDimensions($attribute, $value, $parameters);
    }

    private function validateSlimOutputDimensions($value, array $parameters): bool
    {
        $file = json_decode($value)->output;

        $width = $file->width;
        $height = $file->height;

        return $this->dimensionsCheck($width, $height, $parameters);
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    private function addCustomValidationMessage($attribute, $params, $validator): void
    {
        $validator->setCustomMessages([
            'file_dimensions' => ':attribute heeft niet de juiste afmetingen: '.implode(', ', $this->humanReadableParams($params)),
        ]);
    }

    /**
     * @param $params
     */
    private function humanReadableParams($params): array
    {
        $paramReplacements = [
            'min_width' => 'minimum breedte: %spx',
            'max_width' => 'maximum breedte: %spx',
            'min_height' => 'minimum hoogte: %spx',
            'max_height' => 'maximum hoogte: %spx',
            'ratio' => 'verwachtte verhouding: %s',
        ];

        $humanReadableParams = [];

        foreach ($params as $param) {
            list($property, $value) = explode('=', $param);

            $humanReadableParams[] = isset($paramReplacements[$property])
                ? sprintf($paramReplacements[$property], $value)
                : $param;
        }

        return $humanReadableParams;
    }
}
