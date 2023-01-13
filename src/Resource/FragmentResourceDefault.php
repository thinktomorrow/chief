<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

trait FragmentResourceDefault
{
    use ResourceDefault;

    public static function modelClassName(): string
    {
        return static::class;
    }

    public function getLabel(): string
    {
        $label = (new ResourceKeyFormat(static::modelClassName()))->getLabel();

        return Str::of($label)->remove('fragment')->trim();
    }

    public function getIcon(): string
    {
        return '<svg><use xlink:href="#icon-rectangle-group"></use></svg>';
    }

    public function getHint(): ?string
    {
        return null;
    }

    public function getCategory(): ?string
    {
        return null;
    }

    public function adminView(): View
    {
        return view('chief::manager.windows.fragments.edit');
    }
}
