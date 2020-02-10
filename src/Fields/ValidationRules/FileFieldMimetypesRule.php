<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Thinktomorrow\Chief\Media\Application\MediaRequest;

class FileFieldMimetypesRule extends AbstractMediaFieldRule
{
    public function validate($attribute, $value, $params, $validator): bool
    {
        $value = $this->normalizePayload($value);

        foreach([MediaRequest::NEW, MediaRequest::REPLACE] as $type) {
            foreach($value[$type] as $file) {
                if($file && false !== $this->validateMimetypes($attribute, $file, $params)) {
                    return true;
                }
            }
        }

        $validator->setCustomMessages([
            'filefield_mimetypes' => 'De :attribute is niet het juiste bestandstype. Volgende types zijn geldig: ' . implode(', ', $params),
        ]);

        if(!isset($validator->customAttributes[$attribute])) {
            $validator->addCustomAttributes([
                $attribute => 'afbeelding',
            ]);
        }


        return false;
    }

    public function validateMimetypes($attribute, $value, $parameters)
    {
        if($this->refersToExistingAsset($value)) {
            return $this->validateAssetMimetypes($this->existingAsset($value), $parameters);
        }

        return parent::validateMimetypes($attribute, $value, $parameters);
    }
}
