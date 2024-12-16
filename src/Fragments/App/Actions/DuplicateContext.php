<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Repositories\ContextRepository;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class DuplicateContext
{
    private DuplicateFragment $duplicateFragment;
    private ContextRepository $contextRepository;

    public function __construct(DuplicateFragment $duplicateFragment, ContextRepository $contextRepository)
    {
        $this->duplicateFragment = $duplicateFragment;
        $this->contextRepository = $contextRepository;
    }

    //    public function handle(ReferableModel & FragmentsOwner $sourceModel, string $sourceLocale, ReferableModel & FragmentsOwner $targetModel, string $targetLocale): void
    public function handle(string $sourceContextId, ReferableModel & FragmentsOwner $targetModel): void
    {
        $sourceContext = $this->contextRepository->find($sourceContextId);
        $targetContext = $this->contextRepository->create($targetModel, $sourceContext->getSiteLocales());

        /** @var FragmentModel $fragment */
        foreach ($sourceContext->fragments as $index => $fragment) {
            $this->duplicateFragment->handle($sourceContext, $targetContext, $fragment, $index);
        }
    }
}
