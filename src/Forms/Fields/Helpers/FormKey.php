<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Helpers;

class FormKey
{
    public static function replaceDotsByBrackets(string $value): string
    {
        if (! str_contains($value, '.')) {
            return $value;
        }

        $value = str_replace('.', '][', $value).']';

        return substr_replace($value, '', strpos($value, ']'), 1);
    }

    public static function replaceBracketsByDots(string $value): string
    {
        return str_replace(['[', ']'], ['.', ''], $value);
    }
}
