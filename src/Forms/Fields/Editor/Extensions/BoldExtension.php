<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class BoldExtension
{
    protected string $name = 'bold';

    public static function renderButton(): ?View
    {
        return view('chief-form::fields.editor.buttons.bold');
    }

    public static function renderFooter(): ?View
    {
        return null;
    }

    public static function jsExtensions(): array
    {
        return ['bold'];
    }

    public static function roles(): array
    {
        return [];
    }
}
