<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Sites\HasSiteLocales;

trait FragmentResourceDefault
{
    use ResourceDefault;

    public function getLabel(): string
    {
        $label = (new ResourceKeyFormat(static::modelClassName()))->getLabel();

        return Str::of($label)->remove('fragment')->trim();
    }

    public static function modelClassName(): string
    {
        return static::class;
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

    public function saveLocales(HasSiteLocales $model, array $locales): void
    {
        $model->setLocales($locales);
        $model->save();
    }

    public function allowedFragments(): array
    {
        return [];
    }
}
