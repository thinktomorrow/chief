<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Fragments\BaseFragment;

class Hero extends BaseFragment
{
    public function fields($model): Fields
    {
        return Fields::make([
            Text::make('title'),
            Image::make('thumb'),
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
