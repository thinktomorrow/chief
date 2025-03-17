<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class DuplicateContext
{
    private DuplicateFragment $duplicateFragment;

    private ContextRepository $contextRepository;

    private FragmentRepository $fragmentRepository;

    public function __construct(DuplicateFragment $duplicateFragment, ContextRepository $contextRepository, FragmentRepository $fragmentRepository)
    {
        $this->duplicateFragment = $duplicateFragment;
        $this->contextRepository = $contextRepository;
        $this->fragmentRepository = $fragmentRepository;
    }

    public function handle(string $sourceContextId, ReferableModel&ContextOwner $targetModel): void
    {
        $sourceContext = $this->contextRepository->find($sourceContextId);
        $targetContext = $this->contextRepository->create($targetModel, $sourceContext->getSiteIds());

        /** @var FragmentModel $fragment */
        foreach ($sourceContext->rootFragments as $index => $fragment) {
            $this->duplicateFragment->handle($fragment, $sourceContext->id, $targetContext->id, null, $index);
        }
    }
}
