<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\Extension;
use Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\ExtensionDefault;

class ListStylesExtension implements Extension
{
    use ExtensionDefault;

    protected string $name = 'list_styles';

    public static function renderButton(?array $viewData = []): ?View
    {
        return view('chief-form::fields.editor.buttons.list-styles', $viewData);
    }

    public static function jsExtensions(): array
    {
        return ['list_item', 'bullet_list', 'ordered_list'];
    }
}
