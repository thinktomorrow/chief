<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class ListStylesExtension
{
    protected string $name = 'list_styles';

    public static function renderButton($viewData = []): ?View
    {
        return view('chief-form::fields.editor.buttons.list-styles', $viewData);
    }

    public static function renderFooter(): ?View
    {
        return null;
    }

    public static function jsExtensions(): array
    {
        return ['list_item', 'bullet_list', 'ordered_list'];
    }

    public static function roles(): array
    {
        return [];
    }
}
