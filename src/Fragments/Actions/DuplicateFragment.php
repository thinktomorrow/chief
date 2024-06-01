<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Thinktomorrow\Chief\Fragments\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\Repositories\FragmentRepository;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Fragments\Events\FragmentDuplicated;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyAdded;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;

class DuplicateFragment
{
    private ContextRepository $contextRepository;
    private FragmentRepository $fragmentRepository;
    private AttachFragment $attachFragment;
    private AddAsset $addAsset;

    public function __construct(ContextRepository $contextRepository, FragmentRepository $fragmentRepository, AttachFragment $attachFragment, AddAsset $addAsset)
    {
        $this->contextRepository = $contextRepository;
        $this->fragmentRepository = $fragmentRepository;
        $this->attachFragment = $attachFragment;
        $this->addAsset = $addAsset;
    }

    /**
     * Duplicate a fragment
     * Nested fragments are duplicated as well
     * @throws FragmentAlreadyAdded
     */
    public function handle(ContextModel $sourceContext, ContextModel $targetContext, FragmentModel $fragmentModel, int $index, bool $forceDuplicateSharedFragment = false): void
    {
        // If it's already a shared fragment, we'll use the original and share it as well
        if (! $forceDuplicateSharedFragment && $fragmentModel->isShared()) {
            $this->attachFragment->handle($targetContext->id, $fragmentModel->id, $index);

            return;
        }

        // Otherwise do a full copy of the fragment instead
        $duplicatedFragmentModel = $fragmentModel->replicate();
        $duplicatedFragmentModel->id = $this->fragmentRepository->nextId();
        $duplicatedFragmentModel->save();

        $this->attachFragment->handle($targetContext->id, $duplicatedFragmentModel->id, $index);

        foreach ($fragmentModel->assetRelation()->get() as $asset) {
            $this->addAsset->handle($duplicatedFragmentModel, $asset, $asset->pivot->type, $asset->pivot->locale, $asset->pivot->order, $asset->pivot->data);
        }

        event(new FragmentDuplicated($fragmentModel->id, $duplicatedFragmentModel->id, $sourceContext->id, $targetContext->id));

        // Handle nested fragments
        // TODO: contexts will no longer be the method by which fragments contain nested fragments. We will use the adjacent structure instead.
        if(($fragment = $this->fragmentRepository->find($fragmentModel->id)) instanceof FragmentsOwner) {

            $duplicatedFragment = $this->fragmentRepository->find($duplicatedFragmentModel->id);

            if($fragmentContext = $this->contextRepository->findByFragmentOwner($fragment)) {
                app(DuplicateContext::class)->handle(
                    $fragmentContext->id,
                    $duplicatedFragment,
                    $targetContext->locale
                );
            }
        }
    }
}