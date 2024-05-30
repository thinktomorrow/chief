<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class UnderlineExtension
{
    protected string $name = 'underline';

    public static function renderButton(): ?View
    {
        return view('chief-form::fields.editor.buttons.underline');
    }

    public static function renderFooter(): ?View
    {
        return null;
    }

    public static function jsExtensions(): array
    {
        return ['underline'];
    }

    public static function roles(): array
    {
        return [];
    }
}
