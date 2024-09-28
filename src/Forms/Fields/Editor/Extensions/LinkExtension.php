<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class LinkExtension implements Extension
{
    use ExtensionDefault;

    protected string $name = 'link';

    public static function renderButton(?array $viewData = []): ?View
    {
        return view('chief-form::fields.editor.buttons.link', $viewData);
    }

    public static function jsExtensions(): array
    {
        return ['link'];
    }
}
