<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Settings;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class Homepage
{
    public static function is($model, string $locale = null): bool
    {
        $homepageValue = chiefSetting(Setting::HOMEPAGE, $locale);

        if (! $homepageValue || ! is_object($model) || ! $model instanceof ReferableModel) {
            return false;
        }

        return $model->modelReference()->is($homepageValue);
    }

    public static function url($locale = null): string
    {
        if ($id = chiefSetting(Setting::HOMEPAGE, $locale)) {
            return ModelReference::fromString($id)->instance()->url($locale);
        }

        return '';
    }
}
