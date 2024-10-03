<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;

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
        return Blade::render('<x-chief::icon.folder-library />');
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
