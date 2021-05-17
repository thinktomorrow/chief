<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Events\FragmentRemovedFromContext;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyRemoved;

final class RemoveFragmentModelFromContext
{
    public function handle(Model $owner, FragmentModel $fragmentModel): void
    {
        if (! $context = ContextModel::ownedBy($owner)) {
            throw new \InvalidArgumentException('No context model found for owner ' . get_class($owner) . ' - ' . $owner->id);
        }

        if (! $context->fragments()->where('id', $fragmentModel->id)->exists()) {
            throw new FragmentAlreadyRemoved('Fragment [' . $fragmentModel->id . '] does not exist for owner [' . $owner->modelReference()->get().']');
        }

        $context->fragments()->detach($fragmentModel->id);

        event(new FragmentRemovedFromContext($fragmentModel->id, $context->id));
    }
}
