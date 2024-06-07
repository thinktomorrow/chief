<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class ParagraphStylesExtension
{
    protected string $name = 'paragraph_styles';

    public static function renderButton($viewData): ?View
    {
        return view('chief-form::fields.editor.buttons.paragraph-styles', $viewData);
    }

    public static function renderFooter(): ?View
    {
        return null;
    }

    public static function jsExtensions(): array
    {
        return ['heading'];
    }

    public static function roles(): array
    {
        return [];
    }
}
