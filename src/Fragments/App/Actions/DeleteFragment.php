<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\AssetLibrary\Application\DetachAsset;
use Thinktomorrow\Chief\Fragments\App\Queries\GetOwningModels;
use Thinktomorrow\Chief\Fragments\Domain\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentRepository;

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

    /**
     * Delete the fragment from this given owner. If this fragment is not
     * shared by other owners, it will be deleted entirely.
     */
    public function handle(string $fragmentId): void
    {
        $fragment = $this->fragmentRepository->find($fragmentId);

        try {
            $this->detachAsset->handleAll($fragment->fragmentModel());
        } catch (\Exception $e) {
            report($e);
        }

        $fragment->fragmentModel()->delete();
    }

    public function onFragmentDetached(FragmentDetached $event)
    {
        // By now the fragment is removed from the desired owning context. Here we check
        // that if the fragment is still shared (used by another context) then leave britney alone!
        // If the fragment is no longer shared, it will be deleted entirely.
        if ($this->getOwningModels->getCount($event->fragmentId) > 0) {
            return;
        }

        $this->handle($event->fragmentId);
    }
}
