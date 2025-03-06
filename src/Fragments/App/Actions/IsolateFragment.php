<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;

/**
 * Takes a shared fragment and duplicates it as
 * an isolated fragment for the given context
 */
final class IsolateFragment
{
    private DuplicateFragment $duplicateFragment;

    private DetachFragment $detachFragment;

    private FragmentRepository $fragmentRepository;

    private ContextRepository $contextRepository;

    public function __construct(ContextRepository $contextRepository, FragmentRepository $fragmentRepository, DuplicateFragment $duplicateFragment, DetachFragment $detachFragment)
    {
        $this->duplicateFragment = $duplicateFragment;
        $this->detachFragment = $detachFragment;
        $this->fragmentRepository = $fragmentRepository;
        $this->contextRepository = $contextRepository;
    }

    public function handle(string $contextId, string $fragmentId): void
    {
        $context = $this->contextRepository->find($contextId);
        $fragment = $this->fragmentRepository->findByContext($fragmentId, $contextId);

        $parentId = $fragment->getFragmentModel()->pivot->parent_id;
        $order = $fragment->getFragmentModel()->pivot->order;

        // Duplicate the shared fragment first
        $this->duplicateFragment->handle(
            $fragment->getFragmentModel(),
            $context->id,
            $context->id,
            $parentId,
            $order,
            true
        );

        // Now remove the shared version from current context
        $this->detachFragment->handle($contextId, $fragmentId);
    }
}
