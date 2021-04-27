<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Actions\AddFragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;

class DuplicateModel
{
    private FragmentRepository $fragmentRepository;
    private AddFragmentModel $addFragmentModel;

    public function __construct(FragmentRepository $fragmentRepository, AddFragmentModel $addFragmentModel)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->addFragmentModel = $addFragmentModel;
    }

    public function handle(Model $model): void
    {
        // Otherwise do a full copy of the fragment instead
        $copiedModel = $model->replicate();
        $copiedModel->id = $this->fragmentRepository->nextId();
        $copiedModel->save();

        // Assets
//        foreach ($fragment->assets() as $asset) {
//            $copiedFragment->assetRelation()->attach($asset, ['type' => $asset->pivot->type, 'locale' => $asset->pivot->locale, 'order' => $asset->pivot->order]);
//        }

        // Handle nested fragments
//        foreach ($this->fragmentRepository->getByOwner($fragment) as $i => $nestedFragment) {
//            $this->handle($nestedFragment->fragmentModel(), $copiedFragment, $i);
//        }
    }
}
