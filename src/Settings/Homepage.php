<?php

namespace Thinktomorrow\Chief\Settings;

use Thinktomorrow\Chief\Settings\Setting;
use Thinktomorrow\Chief\FlatReferences\FlatReferenceFactory;
use Thinktomorrow\Chief\FlatReferences\ProvidesFlatReference;

class Homepage
{
    public static function is($model, string $locale = null): bool
    {
        $homepageValue = chiefSetting(Setting::HOMEPAGE, $locale);

        if (!$homepageValue || !is_object($model) || !$model instanceof ProvidesFlatReference) {
            return false;
        }

        return $model->flatReference()->is($homepageValue);
    }

    public static function url($locale = null): string
    {
        if ($id = chiefSetting(Setting::HOMEPAGE, $locale)) {
            return FlatReferenceFactory::fromString($id)->instance()->url($locale);
        }
        return '';
    }
}
