<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class ItalicExtension implements Extension
{
    use ExtensionDefault;

    protected string $name = 'italic';

    public static function renderButton(?array $viewData = []): ?View
    {
        return view('chief-form::fields.editor.buttons.italic', $viewData);
    }

    public static function jsExtensions(): array
    {
        return ['italic'];
    }
}
