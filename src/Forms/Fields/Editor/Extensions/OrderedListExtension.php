<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class OrderedListExtension
{
    protected string $name = 'ordered_list';

    public static function renderButton(): ?View
    {
        return view('chief-form::fields.editor.buttons.ordered-list');
    }

    public static function renderFooter(): ?View
    {
        return null;
    }

    public static function jsExtensions(): array
    {
        return ['list_item', 'ordered_list'];
    }

    public static function roles(): array
    {
        return [];
    }
}
