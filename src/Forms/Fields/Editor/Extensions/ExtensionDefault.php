<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

trait ExtensionDefault
{
    public static function renderButton(?array $viewData = []): ?View
    {
        return null;
    }

    public static function renderFooter(): ?View
    {
        return null;
    }

    public static function jsExtensions(): array
    {
        return [];
    }

    public static function roles(): array
    {
        return ['admin'];
    }
}
