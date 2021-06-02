<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Actions\AddFragmentModel;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentDuplicated;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyAdded;

class DuplicateFragment
{
    private FragmentRepository $fragmentRepository;
    private AddFragmentModel $addFragmentModel;
    private DuplicateModel $duplicateModel;

    public function __construct(FragmentRepository $fragmentRepository, AddFragmentModel $addFragmentModel, DuplicateModel $duplicateModel)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->addFragmentModel = $addFragmentModel;
        $this->duplicateModel = $duplicateModel;
    }

    /**
     * Duplicate a fragment
     *
     * When fragments contains nested fragments:
     * - a nested static fragment is always duplicated (e.g. text, snippet)
     * - a nested dynamic fragment is not duplicated but shared (e.g. article, quote)
     *
     * @param Model $targetModel
     * @param FragmentModel $fragmentModel
     * @param int $index
     * @param bool $forceDuplicateDynamicFragment
     * @param int $level
     * @throws FragmentAlreadyAdded
     */
    public function handle(Model $targetModel, FragmentModel $fragmentModel, int $index, bool $forceDuplicateDynamicFragment = false, $level = 0): void
    {
        if (! $contextModel = ContextModel::ownedBy($targetModel)) {
            $contextModel = ContextModel::createForOwner($targetModel);
        }

        // If it's a shareable fragment, we'll use the original
        if (! $forceDuplicateDynamicFragment && ! $fragmentModel->refersToStaticObject()) {
            $this->addFragmentModel->handle($targetModel, $fragmentModel, $index);

            return;
        }

        // Otherwise do a full copy of the fragment instead
        $copiedFragment = $fragmentModel->replicate();
        $copiedFragment->id = $this->fragmentRepository->nextId();

        // TODO: still to check if this is desired that we copy the dynamic model as well...
        if ($copiedFragment->refersToDynamicModel()) {
            $model = $this->duplicateModel->handle($this->fragmentRepository->find($fragmentModel->id));
            $copiedFragment->model_reference = $model->modelReference()->getShort();
        }

        $copiedFragment->save();

        $contextModel->fragments()->attach($copiedFragment, ['order' => $index]);

        foreach ($fragmentModel->assets() as $asset) {
            $copiedFragment->assetRelation()->attach($asset, ['type' => $asset->pivot->type, 'locale' => $asset->pivot->locale, 'order' => $asset->pivot->order]);
        }

        event(new FragmentDuplicated($copiedFragment->id, $contextModel->id));

        // Handle nested fragments

        foreach ($this->fragmentRepository->getByOwner($fragmentModel) as $i => $nestedFragment) {
            $this->handle($copiedFragment, $nestedFragment->fragmentModel(), $i, false, ++$level);
        }
    }
}
