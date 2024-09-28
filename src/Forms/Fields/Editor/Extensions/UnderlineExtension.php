<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\Extension;
use Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\ExtensionDefault;

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
