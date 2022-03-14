<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Common;

class FormKey
{
    public static function replaceDotsByBrackets(string $value): string
    {
        if (! str_contains($value, '.')) {
            return $value;
        }

        // First make sure that any existing brackets are replaced
        $value = static::handleReplaceBracketsByDots($value);

        return static::handleReplaceDotsByBrackets($value);
    }

    public static function replaceBracketsByDots(string $value): string
    {
        // First make sure that any existing dots are replaced
        $value = static::handleReplaceDotsByBrackets($value);

        return static::handleReplaceBracketsByDots($value);
    }

    private static function handleReplaceDotsByBrackets(string $value): string
    {
        $value = str_replace('.', '][', $value).']';

        return substr_replace($value, '', strpos($value, ']'), 1);
    }

    private static function handleReplaceBracketsByDots(string $value): string
    {
        return str_replace(['[', ']'], ['.', ''], $value);
    }
}
