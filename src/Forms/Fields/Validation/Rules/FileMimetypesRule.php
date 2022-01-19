<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation\Rules;

class FileMimetypesRule extends FileRule
{
    public function validate($attribute, array $values, $params, $validator): bool
    {
        foreach ($values as $value) {
            if ($value && false == $this->validateMimetypes($attribute, $value, $params)) {
                $this->addCustomValidationMessage($attribute, $params, $validator);

                return false;
            }
        }

        return true;
    }

    public function validateMimetypes($attribute, $value, $parameters)
    {
        if ($this->refersToExistingAsset($value)) {
            return $this->validateAssetMimetypes($this->existingAsset($value), $parameters);
        }

        if (!$this->isValidFileInstance($value)) {
            return $this->validateSlimMimetypes($value, $parameters);
        }

        return parent::validateMimetypes($attribute, $value, $parameters);
    }

    private function validateSlimMimetypes($value, array $parameters): bool
    {
        $mimetype = json_decode($value)->output->type;

        return (in_array($mimetype, $parameters) ||
            in_array(explode('/', $mimetype)[0] . '/*', $parameters));
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    private function addCustomValidationMessage($attribute, $params, $validator): void
    {
        $validator->setCustomMessages([
            'file_mimetypes' => ':attribute is niet het juiste bestandstype. Volgende types zijn geldig: ' . implode(', ', $params),
        ]);
    }
}
