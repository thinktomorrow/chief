<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes;

use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Fragments\BaseFragment;
use Thinktomorrow\Chief\Fragments\Fragment;

class SnippetStub extends BaseFragment implements Fragment
{
    public static function resourceKey(): string
    {
        return 'snippet-stub';
    }

    public function fields($model): iterable
    {
        yield Text::make('title');
        yield Text::make('title_trans')->locales();
        yield File::make('thumb')->locales();
        yield Repeat::make('links')->items([
            Text::make('title')->locales(),
            Text::make('url'),
        ]);
    }

    public function getTitle()
    {
        return $this->getFragmentModel()->title;
    }

    public function viewKey(): string
    {
        return 'snippet_stub';
    }

    public function dynamicLocaleFallback(): ?string
    {
        return 'en';
    }
}
