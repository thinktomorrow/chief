<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Symfony\Component\HttpFoundation\File\File;
use Thinktomorrow\Chief\Media\Application\MediaRequest;

class FileFieldMaxRule extends AbstractMediaFieldRule
{
    public function validate($attribute, $value, $params, $validator): bool
    {
        $value = $this->normalizePayload($value);

        foreach([MediaRequest::NEW, MediaRequest::REPLACE] as $type) {
            foreach($value[$type] as $file) {
                if($file && false !== $this->validateMax($attribute, $file, $params)) {
                    return true;
                }
            }
        }

        $validator->setCustomMessages([
            'filefield_max' => 'De :attribute is te groot en dient kleiner te zijn dan ' . implode(',',$params) .'Kb.',
        ]);

        if(!isset($validator->customAttributes[$attribute])) {
            $validator->addCustomAttributes([
                $attribute => 'afbeelding',
            ]);
        }


        return false;
    }

    public function validateMax($attribute, $value, $parameters)
    {
        if($this->refersToExistingAsset($value)) {
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
        if(!$value instanceof File) {
            throw new \InvalidArgumentException('Value is expected to be of type ' . File::class);
        }

        return $value->getSize() / 1024;
    }
}
