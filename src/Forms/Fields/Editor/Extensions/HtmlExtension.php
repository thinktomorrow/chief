<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class HtmlExtension implements Extension
{
    use ExtensionDefault;

    protected string $name = 'html';

    public static function renderButton(?array $viewData = []): ?View
    {
        return view('chief-form::fields.editor.buttons.html', $viewData);
    }

    public static function renderFooter(): ?View
    {
        return view('chief-form::fields.editor.footers.html');
    }
}
