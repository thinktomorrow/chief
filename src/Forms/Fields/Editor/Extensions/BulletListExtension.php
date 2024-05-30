<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class BulletListExtension
{
    protected string $name = 'bullet_list';

    public static function renderButton(): ?View
    {
        return view('chief-form::fields.editor.buttons.bullet-list');
    }

    public static function renderFooter(): ?View
    {
        return null;
    }

    public static function jsExtensions(): array
    {
        return ['list_item', 'bullet_list'];
    }

    public static function roles(): array
    {
        return [];
    }
}
