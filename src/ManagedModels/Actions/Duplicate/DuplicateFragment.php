<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Actions\AddFragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;

class DuplicateFragment
{
    private FragmentRepository $fragmentRepository;
    private AddFragmentModel $addFragmentModel;

    public function __construct(FragmentRepository $fragmentRepository, AddFragmentModel $addFragmentModel)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->addFragmentModel = $addFragmentModel;
    }

    public function handle(Model $targetModel, FragmentModel $fragment, int $index): void
    {
        if (!$contextModel = ContextModel::ownedBy($targetModel)) {
            $contextModel = ContextModel::createForOwner($targetModel);
        }
        
        // If it's a shareable fragment, we'll use the original
        if ($fragment->isShared()) {
            $this->addFragmentModel->handle($targetModel, $fragment, $index);
            return;
        }

        // Otherwise do a full copy of the fragment instead
        $copiedFragment = $fragment->replicate();
        $copiedFragment->id = $this->fragmentRepository->nextId();

        if (!$copiedFragment->refersToStaticObject()) {
            // TODO: copy model?????? what about fragments LOOPHOOOOOOLLLLLE
        }

        $copiedFragment->save();

        $contextModel->fragments()->attach($copiedFragment, ['order' => $index]);

        // TODO: keep same order...

        // Assets
//        foreach ($fragment->assets() as $asset) {
//            $copiedFragment->assetRelation()->attach($asset, ['type' => $asset->pivot->type, 'locale' => $asset->pivot->locale, 'order' => $asset->pivot->order]);
//        }

        // Handle nested fragments
        foreach ($this->fragmentRepository->getByOwner($fragment) as $i => $nestedFragment) {
            $this->handle($copiedFragment, $nestedFragment->fragmentModel(), $i);
        }
    }
}
