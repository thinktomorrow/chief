<?php

namespace Thinktomorrow\Chief\Settings;

use Thinktomorrow\Chief\FlatReferences\ProvidesFlatReference;

class Homepage
{
    public static function is($model, string $locale = null): bool
    {
        $homepageValue = chiefSetting(Setting::HOMEPAGE, $locale);

        if(!$homepageValue || !is_object($model) || !$model instanceof ProvidesFlatReference) {
            return false;
        }

        return $model->flatReference()->is($homepageValue);
    }
}
