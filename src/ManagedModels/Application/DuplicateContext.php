<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Application;

use Webmozart\Assert\Assert;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;

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
        if(!$sourceModel instanceof FragmentsOwner || !$context = ContextModel::ownedBy($sourceModel)) return;

        $copiedContext = ContextModel::createForOwner($targetModel);

        /** @var FragmentModel $fragment */
        foreach($context->fragments as $fragment) {
            $copiedFragment = $fragment->replicate(['context_id']);
            $copiedFragment->id = $this->fragmentRepository->nextId();
            $copiedFragment->context_id = $copiedContext->id;
            $copiedFragment->save();

            // Assets
            foreach ($fragment->assets() as $asset) {
                $targetModel->assetRelation()->attach($asset, ['type' => $asset->pivot->type, 'locale' => $asset->pivot->locale, 'order' => $asset->pivot->order]);
            }
        }
    }
}
