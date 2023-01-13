<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\Application\DetachAsset;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;

class DeleteFragment
{
    private FragmentRepository $fragmentRepository;
    private GetOwningModels $getOwningModels;
    private DetachAsset $detachAsset;
    private DetachFragment $detachFragment;

    public function __construct(FragmentRepository $fragmentRepository, GetOwningModels $getOwningModels, DetachFragment $detachFragment, DetachAsset $detachAsset)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->getOwningModels = $getOwningModels;
        $this->detachAsset = $detachAsset;
        $this->detachFragment = $detachFragment;
    }

    /**
     * Delete the fragment from this given owner. If this fragment is not
     * shared by other owners, it will be deleted entirely.
     */
    public function handle(FragmentModel $fragmentModel): void
    {
        try {
            // This gives an error when entity_id contains of integer ids (like for fragmentModel) and uuids.
            $this->detachAsset->detachAll($fragmentModel);
        } catch (\Exception $e) {
            report($e);
        }

        $fragmentModel->delete();
    }

    public function onFragmentDetached(FragmentDetached $event)
    {
        $fragmentable = $this->fragmentRepository->find($event->fragmentModelId);

        // By now the fragment is removed from the desired owning context. Here we check
        // that if the fragment is still shared (used by another context) then leave britney alone!
        // TODO: emit event so we can recheck the metadata for the fragment (aka shared attribute:
        // because maybe the fragment is now only used by only one model so it is no longer shared.
        if ($this->getOwningModels->getCount($fragmentable->fragmentModel()) > 0) {
            return;
        }

        $this->handle($fragmentable->fragmentModel());
    }
}
