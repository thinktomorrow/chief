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
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /> </svg>';
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
