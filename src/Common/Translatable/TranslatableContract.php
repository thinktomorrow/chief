<?php

namespace Thinktomorrow\Chief\Common\Translatable;

interface TranslatableContract
{
    public function getDefaultTranslation($attribute);

    public function updateTranslation($locale, array $values);

    public function saveTranslation($locale, $attribute, $value);

    public function removeTranslation($locale);

    public function getUsedLocales();

    public function getNonUsedLocales();
}
