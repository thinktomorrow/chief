<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Fragments\BaseFragment;

class OwnerAwareFragment extends BaseFragment
{
    public function fields($model): iterable
    {
        yield MultiSelect::make('owner_ref')->options(function () {
            $contextOwner = $this->getContextOwner();

            if (! $contextOwner) {
                return ['missing' => 'missing'];
            }

            return [$contextOwner->modelReference()->get() => $contextOwner->modelReference()->get()];
        });
    }

    public function viewKey(): string
    {
        return 'owner-aware-fragment';
    }
}
