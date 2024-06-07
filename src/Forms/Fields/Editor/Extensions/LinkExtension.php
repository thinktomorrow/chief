<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class LinkExtension
{
    protected string $name = 'link';

    public static function renderButton($viewData = []): ?View
    {
        return view('chief-form::fields.editor.buttons.link', $viewData);
    }

    public static function renderFooter(): ?View
    {
        return null;
    }

    public static function jsExtensions(): array
    {
        return ['link'];
    }

    public static function roles(): array
    {
        return [];
    }
}
