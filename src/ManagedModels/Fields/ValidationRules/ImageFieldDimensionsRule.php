<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\ValidationRules;

class ImageFieldDimensionsRule extends AbstractMediaFieldRule
{
    public function validate($attribute, array $values, $params, $validator): bool
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

        return $this->validateSlimOutputDimensions($attribute, $value, $parameters);
    }

    /**
     * Override Laravel validateDimensions to focus on the ImageField specifics
     */
    private function validateSlimOutputDimensions($attribute, $value, $parameters)
    {
        $file = json_decode($value)->output;

        $width = $file->width;
        $height = $file->height;

        return $this->dimensionsCheck($width, $height, $parameters);
    }

    /**
     * @param $params
     * @return array
     */
    private function humanReadableParams($params): array
    {
        $paramReplacements = [
            'min_width'  => 'minimum breedte: %spx',
            'max_width'  => 'maximum breedte: %spx',
            'min_height' => 'minimum hoogte: %spx',
            'max_height' => 'maximum hoogte: %spx',
            'ratio'      => 'verwachtte verhouding: %s',
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

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    private function addCustomValidationMessage($attribute, $params, $validator): void
    {
        $validator->setCustomMessages([
            'imagefield_dimensions' => ':attribute heeft niet de juiste afmetingen: ' . implode(', ', $this->humanReadableParams($params)),
        ]);

        if (!isset($validator->customAttributes[$attribute])) {
            $validator->addCustomAttributes([
                $attribute => 'afbeelding',
            ]);
        }
    }
}
