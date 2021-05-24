<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Events\SharedFragmentDetached;
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

    public function handle(Model $ownerModel, FragmentModel $fragmentModel, int $order): void
    {
        // Duplicate
        $this->duplicateFragment->handle($ownerModel, $fragmentModel, $order, true);

        // And then delete
        $this->removeFragmentModelFromContext->handle($ownerModel, $fragmentModel);

        event(new SharedFragmentDetached($fragmentModel->id));
    }
}
