<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Fragments\BaseFragment;

class Hero extends BaseFragment
{
    public function renderAdminFragment($owner, $loop, $viewData = []): string
    {
        return 'hero-admin-fragment';
    }

    public function renderFragment($owner, $loop, $viewData = []): string
    {
        return 'hero-fragment';
    }

    public function fields($model): Fields
    {
        return Fields::make([
            Fields\Text::make('title'),
            Fields\Image::make('thumb'),
        ]);
    }

    public function getTitle()
    {
        return $this->getFragmentModel()->title;
    }

    public function viewKey(): string
    {
        return 'hero';
    }
}
