<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyDetached;

class DetachFragment
{
    /**
     * Detach a fragment from a given context.
     * Keep the fragment model itself intact
     */
    public function handle(Model $owner, FragmentModel $fragmentModel): void
    {
        if (! $context = ContextModel::ownedBy($owner)) {
            throw new \InvalidArgumentException('No context model found for owner ' . get_class($owner) . ' - ' . $owner->id);
        }

        if (! $context->fragments()->where('id', $fragmentModel->id)->exists()) {
            throw new FragmentAlreadyDetached('Fragment [' . $fragmentModel->id . '] does not exist for owner [' . $owner->modelReference()->get().']');
        }

        $context->fragments()->detach($fragmentModel->id);

        event(new FragmentDetached($fragmentModel->id, $context->id));
    }
}
