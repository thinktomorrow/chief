<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Thinktomorrow\AssetLibrary\Application\DetachAsset;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentRemovedFromContext;

class DeleteFragment
{
    private FragmentRepository $fragmentRepository;
    private GetOwningModels $getOwningModels;
    private DetachAsset $detachAsset;

    public function __construct(FragmentRepository $fragmentRepository, GetOwningModels $getOwningModels, DetachAsset $detachAsset)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->getOwningModels = $getOwningModels;
        $this->detachAsset = $detachAsset;
    }

    public function handle(int $fragmentId): void
    {
        $fragmentModel = FragmentModel::find($fragmentId);

        $this->detachAsset->detachAll($fragmentModel);

        $fragmentModel->delete();
    }

    public function onFragmentRemovedFromContext(FragmentRemovedFromContext $event)
    {
        $fragmentable = $this->fragmentRepository->find($event->fragmentModelId);

        // By now the fragment is removed from the desired owning context. Here we check
        // that if the fragment is still shared (used by another context) then leave britney alone!
        // TODO: emit event so we can recheck the metadata for the fragment (aka shared attribute:
        // because maybe the fragment is now only used by only one model so it is no longer shared.
        if (count($this->getOwningModels->get($fragmentable->fragmentModel())) > 0) {
            return;
        }

        $this->handle($event->fragmentModelId);
    }
}
