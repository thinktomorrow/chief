<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\Extension;
use Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\ExtensionDefault;

class ParagraphStylesExtension implements Extension
{
    use ExtensionDefault;

    protected string $name = 'paragraph_styles';

    public static function renderButton(?array $viewData = []): ?View
    {
        return view('chief-form::fields.editor.buttons.paragraph-styles', $viewData);
    }

    public static function jsExtensions(): array
    {
        return ['heading'];
    }
}
