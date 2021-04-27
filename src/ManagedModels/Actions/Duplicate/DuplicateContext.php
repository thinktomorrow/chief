<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Thinktomorrow\Chief\Fragments\Actions\AddFragmentModel;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;

class DuplicateContext
{
    private FragmentRepository $fragmentRepository;
    private AddFragmentModel $addFragmentModel;

    public function __construct(FragmentRepository $fragmentRepository, AddFragmentModel $addFragmentModel)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->addFragmentModel = $addFragmentModel;
    }

    public function handle($sourceModel, $targetModel): void
    {
        if (! $sourceModel instanceof FragmentsOwner || ! $context = ContextModel::ownedBy($sourceModel)) {
            return;
        }

        ContextModel::createForOwner($targetModel);

        /** @var FragmentModel $fragment */
        foreach ($context->fragments as $index => $fragment) {

            // If it's a shareable fragment, we'll use the original
            if ($fragment->isShared()) {
                $this->addFragmentModel->handle($targetModel, $fragment->fragmentModel(), $index);

                continue;
            }

            // Otherwise do a copy of the fragment instead
            $copiedFragment = $fragment->replicate();
            $copiedFragment->id = $this->fragmentRepository->nextId();
            $copiedFragment->save();

            // TODO: keep same order...

            // Assets
            foreach ($fragment->assets() as $asset) {
                $targetModel->assetRelation()->attach($asset, ['type' => $asset->pivot->type, 'locale' => $asset->pivot->locale, 'order' => $asset->pivot->order]);
            }
        }
    }
}
