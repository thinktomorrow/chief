<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Tests\Stubs;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Fragments\BaseFragment;
use Thinktomorrow\Chief\Fragments\Fragment;

class RootFragmentStub extends BaseFragment implements Fragment
{
    public function fields($model): iterable
    {
        yield Text::make('title');
    }

    public function viewKey(): string
    {
        return 'root-fragment';
    }
}
