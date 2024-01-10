<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\Resource\Events\FragmentDuplicated;
use Thinktomorrow\Chief\Fragments\Resource\Exceptions\FragmentAlreadyAdded;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentRepository;

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
     *
     * Nested fragments are also duplicated
     *
     * @param Model $targetModel
     * @param FragmentModel $fragmentModel
     * @param int $index
     * @param bool $forceDuplicateSharedFragment
     * @param int $level
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
        if(($fragment = $this->fragmentRepository->find($fragmentModel->id)) instanceof FragmentsOwner) {

            $duplicatedFragment = $this->fragmentRepository->find($duplicatedFragmentModel->id);

            if($fragmentContext = $this->contextRepository->findByOwner($fragment, $sourceContext->locale)) {
                app(DuplicateContext::class)->handle(
                    $fragmentContext->id,
                    $duplicatedFragment,
                    $targetContext->locale
                );
            }
        }
    }
}
