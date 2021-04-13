<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Application;

use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;

class DuplicateContext
{
    /** @var FragmentRepository */
    private FragmentRepository $fragmentRepository;

    public function __construct(FragmentRepository $fragmentRepository)
    {
        $this->fragmentRepository = $fragmentRepository;
    }

    public function handle($sourceModel, $targetModel): void
    {
        if (! $sourceModel instanceof FragmentsOwner || ! $context = ContextModel::ownedBy($sourceModel)) {
            return;
        }

        ContextModel::createForOwner($targetModel);

        /** @var FragmentModel $fragment */
        foreach ($context->fragments as $fragment) {
            $copiedFragment = $fragment->replicate();
            $copiedFragment->id = $this->fragmentRepository->nextId();
            $copiedFragment->save();

            // Assets
            foreach ($fragment->assets() as $asset) {
                $targetModel->assetRelation()->attach($asset, ['type' => $asset->pivot->type, 'locale' => $asset->pivot->locale, 'order' => $asset->pivot->order]);
            }
        }
    }
}