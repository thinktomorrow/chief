<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\FragmentAlreadyAdded;

final class AddFragmentModel
{
    public function handle(Model $owner, FragmentModel $fragmentModel, int $order): void
    {
        if (! $context = ContextModel::ownedBy($owner)) {
            $context = ContextModel::createForOwner($owner);
        }

        if ($context->fragments()->where('id', $fragmentModel->id)->exists()) {
            throw new FragmentAlreadyAdded('Fragment [' . $fragmentModel->id . '] was already added to owner [' . $owner->modelReference()->get().']');
        }

        $context->fragments()->attach($fragmentModel->id, [
            'order' => $order,
        ]);
    }
}
