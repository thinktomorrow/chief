<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;

final class AddFragmentModel
{
    public function handle(Model $owner, FragmentModel $fragmentModel, int $order)
    {
        if (! $context = ContextModel::ownedBy($owner)) {
            $context = ContextModel::createForOwner($owner);
        }

        $context->fragments()->attach($fragmentModel->id, [
            'order' => $order,
        ]);
    }
}
