<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentModel;
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

    public function handle(ReferableModel & FragmentsOwner $sourceModel, string $sourceLocale, ReferableModel & FragmentsOwner $targetModel, string $targetLocale): void
    {
        if(! $sourceContext = $this->contextRepository->findByOwner($sourceModel, $sourceLocale)) {
            return;
        }

        if($this->contextRepository->findByOwner($targetModel, $targetLocale)) {
            throw new \InvalidArgumentException('Cannot duplicate to given target context. Context for [' . $targetModel->modelReference()->get() . ', locale: '.$targetLocale.'] already exists.');
        }

        // Create new context
        $targetContext = $this->contextRepository->createForOwner($targetModel, $targetLocale);

        /** @var FragmentModel $fragment */
        foreach ($sourceContext->fragments as $index => $fragment) {
            $this->duplicateFragment->handle($sourceContext, $targetContext, $fragment, $index);
        }
    }
}
