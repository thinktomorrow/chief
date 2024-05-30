<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class HtmlExtension
{
    protected string $name = 'html';

    public static function renderButton(): ?View
    {
        return view('chief-form::fields.editor.buttons.html');
    }

    public static function renderFooter(): ?View
    {
        return view('chief-form::fields.editor.footers.html');
    }

    public static function jsExtensions(): array
    {
        return [];
    }

    public static function roles(): array
    {
        return [];
    }
}
