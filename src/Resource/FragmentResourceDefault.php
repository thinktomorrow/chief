<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;

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

    public function adminView(): View
    {
        return view('chief::manager.windows.fragments.edit');
    }
}
