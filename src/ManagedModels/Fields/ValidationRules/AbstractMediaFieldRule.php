<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\ValidationRules;

use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Validation\Concerns\ValidatesAttributes;
use Thinktomorrow\Chief\Media\Application\ChecksExistingAssets;

abstract class AbstractMediaFieldRule
{
    use ChecksExistingAssets;

    use ValidatesAttributes;
    use ValidatesExistingAssetAttributes;

    protected function normalizePayloadIncludingExistingMedia($value): array
    {
        return $this->normalizePayload($value, false);
    }

    protected function normalizePayload(array $values, $excludeExistingMedia = true): array
    {
//        if($excludeExistingMedia) {
//            foreach($values as $key => $value) {
//                if($this->looksLikeAnAssetId($key) && $key == $value) {
//                    unset($values[$key]);
//                }
//            }
//        }

        return $values;
    }

    /**
     * Default getSize method
     *
     * Override the default getSize from ValidatesAttributes to avoid calls to a hasRule method
     * For media fields this is not needed anyways.
     *
     * @param $attribute
     * @param $value
     * @return bool|false|float|int
     */
    protected function getSize($attribute, $value)
    {
        if ($value instanceof File) {
            return $value->getSize() / 1024;
        }

        return mb_strlen($value);
    }
}
