<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes;

use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Fragments\BaseFragment;
use Thinktomorrow\Chief\Fragments\Fragment;

class FragmentStub extends BaseFragment implements Fragment
{
    public function renderAdminFragment($owner, $loop, $viewData = []): string
    {
        return '';
    }

    public function renderFragment($owner, $loop, $viewData = []): string
    {
        return 'fragment-stub-'.$this->id.' ';
    }

    public function allowedFragments(): array
    {
        return [];
    }

    public static function resourceKey(): string
    {
        return 'fragment-stub';
    }

    public function fields($model): Fields
    {
        return Fields::make([
            Fields\Text::make('title')->tag('create'),
        ]);
    }

    public function viewKey(): string
    {
        return 'fragmentable_stub';
    }
}
