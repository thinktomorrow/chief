<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\Extension;
use Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\ExtensionDefault;

class BoldExtension implements Extension
{
    use ExtensionDefault;

    protected string $name = 'bold';

    public static function renderButton(?array $viewData = []): ?View
    {
        return view('chief-form::fields.editor.buttons.bold', $viewData);
    }

    public static function jsExtensions(): array
    {
        return ['bold'];
    }
}
