<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Tests\Stubs;

use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Fragments\BaseFragment;
use Thinktomorrow\Chief\Fragments\Fragment;

class RootFragmentStub extends BaseFragment implements Fragment
{
    public function fields($model): iterable
    {
        yield Fields\Text::make('title');
    }

    public function viewKey(): string
    {
        return 'root-fragment';
    }
}
