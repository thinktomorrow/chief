<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Concerns\Translatable;

interface TranslatableContract
{
    public function getDefaultTranslation($attribute);

    public function updateTranslation($locale, array $values);

    public function saveTranslation($locale, $attribute, $value);

    public function removeTranslation($locale);

    public function getUsedLocales();

    public function getNonUsedLocales();

    public static function availableLocales();
}
