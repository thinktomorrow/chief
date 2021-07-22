<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\ManagedModels\Actions\Duplicate\DuplicateFragment;

final class UnshareFragment
{
    private DuplicateFragment $duplicateFragment;
    private DetachFragment $detachFragment;

    public function __construct(DuplicateFragment $duplicateFragment, DetachFragment $detachFragment)
    {
        $this->duplicateFragment = $duplicateFragment;
        $this->detachFragment = $detachFragment;
    }

    public function handle(Model $ownerModel, FragmentModel $fragmentModel): void
    {
        $order = $this->findFragmentOrderInContext($ownerModel, $fragmentModel);

        // Duplicate the shared fragment first
        $this->duplicateFragment->handle($ownerModel, $fragmentModel, $order, true);

        // Now remove the shared version from current context
        $this->detachFragment->handle($ownerModel, $fragmentModel);
    }

    private function findFragmentOrderInContext(Model $owner, FragmentModel $fragmentModel): int
    {
        $context = ContextModel::ownedBy($owner);

        // We look up the fragment from within the context so we have the order value on the pivot table available
        $fragmentModel = $context->findFragmentModel($fragmentModel->id);

        return (int) $fragmentModel->pivot->order ?? 0;
    }
}
