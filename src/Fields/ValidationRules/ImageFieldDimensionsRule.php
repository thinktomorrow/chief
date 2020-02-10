<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Thinktomorrow\Chief\Media\Application\MediaRequest;

class ImageFieldDimensionsRule extends AbstractMediaFieldRule
{
    public function validate($attribute, $value, $params, $validator): bool
    {
        $value = $this->normalizePayload($value);

        foreach ([MediaRequest::NEW, MediaRequest::REPLACE] as $type) {
            foreach ($value[$type] as $file) {
                if ($file && false !== $this->validateDimensions($attribute, $file, $params)) {
                    return true;
                }
            }
        }

        $validator->setCustomMessages([
            'imagefield_dimensions' => 'De :attribute heeft niet de juiste afmetingen: ' . implode(',', $this->humanReadableParams($params)),
        ]);

        if (!isset($validator->customAttributes[$attribute])) {
            $validator->addCustomAttributes([
                $attribute => 'afbeelding',
            ]);
        }


        return false;
    }

    /**
     * Override Laravel validateDimensions to focus on the ImageField specifics
     */
    public function validateDimensions($attribute, $value, $parameters)
    {
        $file = json_decode($value)->output;

        $width = $file->width;
        $height = $file->height;

        $parameters = $this->parseNamedParameters($parameters);

        if ($this->failsBasicDimensionChecks($parameters, $width, $height) ||
            $this->failsRatioCheck($parameters, $width, $height)) {
            return false;
        }

        return true;
    }

    /**
     * @param $params
     * @return array
     */
    private function humanReadableParams($params): array
    {
        $paramReplacements = [
            'min_width'  => 'minimum breedte: %s Kb',
            'max_width'  => 'maximum breedte: %s Kb',
            'min_height' => 'minimum hoogte: %s Kb',
            'max_height' => 'maximum hoogte: %s Kb',
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


}
