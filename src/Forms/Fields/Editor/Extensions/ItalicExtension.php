<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class ItalicExtension
{
    protected string $name = 'italic';

    public static function renderButton(): ?View
    {
        return view('chief-form::fields.editor.buttons.italic');
    }

    public static function renderFooter(): ?View
    {
        return null;
    }

    public static function jsExtensions(): array
    {
        return ['italic'];
    }

    public static function roles(): array
    {
        return [];
    }
}
