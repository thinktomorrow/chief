<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

interface Extension
{
    /**
     * The button is rendered in a toolbar above the editor. This should trigger the main action of the extension.
     */
    public static function renderButton(?array $viewData = []): ?View;

    /**
     * The footer is rendered below the editor.
     * Can be used to add additional HTML, e.g. the character count element.
     */
    public static function renderFooter(): ?View;

    /**
     * The names of the necessary TipTap JavaScript extensions for this to work.
     * These will be loaded when the editor is initialized.
     *
     * @return string
     */
    public static function jsExtensions(): array;

    /**
     * The roles that are required to use this extension, e.g. you need role 'dev' to use the html extension.
     *
     * @return string
     */
    public static function roles(): array;
}
