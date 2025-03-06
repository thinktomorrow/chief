<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentDuplicated;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyAdded;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;

class DuplicateFragment
{
    private FragmentRepository $fragmentRepository;

    private AttachFragment $attachFragment;

    private AddAsset $addAsset;

    private array $fragmentCollections;

    public function __construct(FragmentRepository $fragmentRepository, AttachFragment $attachFragment, AddAsset $addAsset)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->attachFragment = $attachFragment;
        $this->addAsset = $addAsset;
    }

    /**
     * Duplicate a fragment to the root of the new context
     * Nested fragments are duplicated as well
     *
     * @throws FragmentAlreadyAdded
     */
    public function handle(FragmentModel $fragmentModel, string $sourceContextId, string $targetContextId, ?string $parentFragmentId, int $index, bool $forceDuplicateSharedFragment = false): void
    {
        // If it's already a shared fragment, we'll use the original and share it as well
        if (! $forceDuplicateSharedFragment && $fragmentModel->isShared()) {
            $this->attachFragment->handle($targetContextId, $fragmentModel->id, $parentFragmentId, $index);

            return;
        }

        // Otherwise do a full copy of the fragment instead
        $duplicatedFragmentModel = $fragmentModel->replicate();
        $duplicatedFragmentModel->id = $this->fragmentRepository->nextId();
        $duplicatedFragmentModel->save();

        $this->attachFragment->handle($targetContextId, $duplicatedFragmentModel->id, $parentFragmentId, $index);

        foreach ($fragmentModel->assetRelation()->get() as $asset) {
            $this->addAsset->handle($duplicatedFragmentModel, $asset, $asset->pivot->type, $asset->pivot->locale, $asset->pivot->order, $asset->pivot->data);
        }

        event(new FragmentDuplicated($fragmentModel->id, $duplicatedFragmentModel->id, $sourceContextId, $targetContextId));

        $this->handleNestedFragments($fragmentModel, $duplicatedFragmentModel, $sourceContextId, $targetContextId, $forceDuplicateSharedFragment);
    }

    private function handleNestedFragments(FragmentModel $fragmentModel, FragmentModel $duplicatedFragmentModel, $sourceContextId, $targetContextId, bool $forceDuplicateSharedFragment = false): void
    {
        $children = $this->getFragmentCollection($sourceContextId)
            ->find(fn ($fragment) => $fragment->id === $fragmentModel->id)
            ->getChildNodes();

        foreach ($children as $child) {
            $this->handle($child->getFragmentModel(), $sourceContextId, $targetContextId, $duplicatedFragmentModel->id, $child->getFragmentModel()->pivot->order, $forceDuplicateSharedFragment);
        }
    }

    private function getFragmentCollection(string $contextId)
    {
        if (isset($this->fragmentCollections[$contextId])) {
            return $this->fragmentCollections[$contextId];
        }

        return $this->fragmentCollections[$contextId] = $this->fragmentRepository->getFragmentCollection($contextId);
    }
}
