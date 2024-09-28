<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class UnderlineExtension implements Extension
{
    use ExtensionDefault;

    protected string $name = 'underline';

    public static function renderButton(?array $viewData = []): ?View
    {
        return view('chief-form::fields.editor.buttons.underline', $viewData);
    }

    public static function jsExtensions(): array
    {
        return ['underline'];
    }
}
