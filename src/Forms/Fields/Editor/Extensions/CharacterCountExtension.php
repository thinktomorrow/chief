<?php

namespace Thinktomorrow\Chief\Forms\Fields\Editor\Extensions;

use Illuminate\Contracts\View\View;

class CharacterCountExtension
{
    protected string $name = 'character_count';

    public static function renderButton(): ?View
    {
        return null;
    }

    public static function renderFooter(): ?View
    {
        return view('chief-form::fields.editor.footers.character-count');
    }

    public static function jsExtensions(): array
    {
        return [];
    }

    public static function roles(): array
    {
        return [];
    }
}
