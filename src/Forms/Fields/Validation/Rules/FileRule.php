<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation\Rules;

use Illuminate\Validation\Concerns\ValidatesAttributes;
use Symfony\Component\HttpFoundation\File\File;
use Thinktomorrow\Chief\Forms\Fields\Media\Application\ChecksExistingAssets;

abstract class FileRule
{
    use ChecksExistingAssets;
    use ValidatesAttributes;
    use ValidatesExistingAssetAttributes;

    /**
     * Override the default getSize from ValidatesAttributes to avoid calls to a hasRule method
     * For files this is not needed anyway.
     *
     * @param $attribute
     * @param $value
     * @param numeric $value
     *
     * @return bool|false|float|int
     */
    protected function getSize(string $attribute, $value)
    {
        if ($value instanceof File) {
            return $value->getSize() / 1024;
        }

        return mb_strlen($value);
    }

    /**
     * @param $value
     * @return float|int
     */
    protected function getSlimImageSize($value)
    {
//        $file = json_decode($value)->output;
//        $estimatedSize = strlen(base64_decode($file->image));
//        return $estimatedSize / 1024;

        $inputData = json_decode($value)->input;

        // size in Kilobytes (slim component already provides a size that, due to the way slim stored this,
        //  we need reduce to kilobytes by dividing 1000 instead of the expected 1024.
        return $inputData->size / 1000;
    }
}
