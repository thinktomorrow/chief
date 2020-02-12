<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Thinktomorrow\Chief\Media\Application\MediaRequest;

class ImageFieldMimetypesRule extends AbstractMediaFieldRule
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
            'imagefield_mimetypes' => 'De :attribute is niet het juiste bestandstype. Volgende types zijn geldig: ' . implode(', ', $params),
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

        return $this->validateSlimMimetypes($attribute, $value, $parameters);
    }

    /**
     * Override Laravel validateDimensions to focus on the ImageField specifics
     */
    private function validateSlimMimetypes($attribute, $value, $parameters): bool
    {
        $mimetype = json_decode($value)->output->type;

        return (in_array($mimetype, $parameters) ||
            in_array(explode('/', $mimetype)[0].'/*', $parameters));
    }
}
