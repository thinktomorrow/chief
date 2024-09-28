<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class CharacterCountExtension implements Extension
{
    use ExtensionDefault;

    protected string $name = 'character_count';

    public static function renderFooter(): ?View
    {
        return view('chief-form::fields.editor.footers.character-count');
    }
}
