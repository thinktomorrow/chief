<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Validation\Rules;

use Illuminate\Validation\Concerns\ValidatesAttributes;
use Symfony\Component\HttpFoundation\File\File;

abstract class FileRule
{
    use ValidatesAttributes;

    /**
     * Override the default getSize from ValidatesAttributes to avoid
     * calls to a hasRule method For files this is not needed anyway.
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
}
