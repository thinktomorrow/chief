<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation\Rules;

class FileDimensionsRule extends FileRule
{
    public function validate($attribute, array $values, $params, $validator): bool
    {
        foreach ($values as $value) {
            if ($value && $this->validateDimensions($attribute, $value, $params) === false) {
                $this->addCustomValidationMessage($attribute, $params, $validator);

                return false;
            }
        }

        return true;
    }

    //    public function validateDimensions($attribute, $value, $parameters)
    //    {
    //        return parent::validateDimensions($attribute, $value, $parameters);
    //    }

    private function addCustomValidationMessage($attribute, $params, $validator): void
    {
        $validator->setCustomMessages([
            'file_dimensions' => ':attribute heeft niet de juiste afmetingen: '.implode(', ', $this->humanReadableParams($params)),
        ]);
    }

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
            [$property, $value] = explode('=', $param);

            $humanReadableParams[] = isset($paramReplacements[$property])
                ? sprintf($paramReplacements[$property], $value)
                : $param;
        }

        return $humanReadableParams;
    }
}
