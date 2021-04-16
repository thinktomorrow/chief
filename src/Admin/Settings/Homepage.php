<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Settings;

final class Homepage
{
    public static function is($model, string $locale = null): bool
    {
        return SelectedModel::is($model, Setting::HOMEPAGE, $locale);
    }

    public static function url($locale = null): string
    {
        if ($instance = SelectedModel::fromKey(Setting::HOMEPAGE, $locale)) {
            return $instance->url($locale);
        }

        return '';
    }
}
