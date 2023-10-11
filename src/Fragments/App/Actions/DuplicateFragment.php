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
use Thinktomorrow\Chief\Locale\ChiefLocaleConfig;

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
    public function handle(ContextModel $sourceContext, ContextModel $targetContext, FragmentModel $fragmentModel, int $index, bool $forceDuplicateSharedFragment = false, $level = 0): void
    {
        // If it's already a shared fragment, we'll use the original and share it as well
        if (! $forceDuplicateSharedFragment && $fragmentModel->isShared()) {
            $this->attachFragment->handle($targetContext->id, $fragmentModel->id, $index);

            return;
        }

        // Otherwise do a full copy of the fragment instead
        $copiedFragmentModel = $fragmentModel->replicate();
        $copiedFragmentModel->id = $this->fragmentRepository->nextId();
        $copiedFragmentModel->save();

        $this->attachFragment->handle($targetContext->id, $copiedFragmentModel->id, $index);

        foreach ($fragmentModel->assetRelation()->get() as $asset) {
            $this->addAsset->handle($copiedFragmentModel, $asset, $asset->pivot->type, $asset->pivot->locale, $asset->pivot->order, $asset->pivot->data);
        }

        event(new FragmentDuplicated($sourceContext->id, $fragmentModel->id, $targetContext->id, $copiedFragmentModel->id, ));

        // Handle nested fragments
        if(($fragment = $this->fragmentRepository->find($fragmentModel->id)) instanceof FragmentsOwner) {

            $copiedFragment = $this->fragmentRepository->find($copiedFragmentModel->id);

            app(DuplicateContext::class)->handle(
                $fragment,
                ChiefLocaleConfig::getDefaultLocale(),
                $copiedFragment,
                ChiefLocaleConfig::getDefaultLocale()
            );
        }


//
//        foreach ($nestedContext->fragments()->get() as $i => $nestedFragment) {
//            $this->handle($copiedFragment, $nestedFragment->fragmentModel(), $i, false, ++$level);
//        }
    }
}
