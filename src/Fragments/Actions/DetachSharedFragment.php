<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Events\SharedFragmentDetached;
use Thinktomorrow\Chief\Fragments\Events\FragmentRemovedFromContext;
use Thinktomorrow\Chief\ManagedModels\Actions\Duplicate\DuplicateFragment;

final class DetachSharedFragment
{
    private DuplicateFragment $duplicateFragment;
    private RemoveFragmentModelFromContext $removeFragmentModelFromContext;

    public function __construct(DuplicateFragment $duplicateFragment, RemoveFragmentModelFromContext $removeFragmentModelFromContext)
    {
        $this->duplicateFragment = $duplicateFragment;
        $this->removeFragmentModelFromContext = $removeFragmentModelFromContext;
    }

    public function handle(Model $ownerModel, FragmentModel $fragmentModel): void
    {
        $order = $this->findFragmentOrderInContext($ownerModel, $fragmentModel);

        // Duplicate
        $this->duplicateFragment->handle($ownerModel, $fragmentModel, $order, true);

        // And then delete
        $this->removeFragmentModelFromContext->handle($ownerModel, $fragmentModel);

        event(new SharedFragmentDetached($fragmentModel->id));
    }

    private function findFragmentOrderInContext(Model $owner, FragmentModel $fragmentModel): int
    {
        $context = ContextModel::ownedBy($owner);

        // We look up the fragment from within the context so we have the order value on the pivot table available
        $fragmentModel = $context->findFragmentModel($fragmentModel->id);

        return (int) $fragmentModel->pivot->order ?? 0;
    }
}
