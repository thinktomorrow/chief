<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentAttached;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Events\FragmentDuplicated;

class UpdateFragmentMetadata
{
    private ContextRepository $contextRepository;

    private FragmentRepository $fragmentRepository;

    public function __construct(ContextRepository $contextRepository, FragmentRepository $fragmentRepository)
    {
        $this->contextRepository = $contextRepository;
        $this->fragmentRepository = $fragmentRepository;
    }

    public function onFragmentAdded(FragmentAttached $event): void
    {
        $this->updateSharedState($event->fragmentId);
    }

    public function onFragmentDuplicated(FragmentDuplicated $event): void
    {
        $this->updateSharedState($event->fragmentId);
    }

    public function onFragmentDetached(FragmentDetached $event): void
    {
        $this->updateSharedState($event->fragmentId);
    }

    private function updateSharedState(string $fragmentId): void
    {
        // Which can happen when after detaching the fragment from its last context, it was deleted.
        if (! $this->fragmentRepository->exists($fragmentId)) {
            return;
        }

        $fragmentCount = $this->contextRepository->getContextsByFragment($fragmentId)->count();

        // We consider shared state only when the fragment belongs to two or more pages.
        // If the fragment belongs to multiple contexts of the same page, it is still considered shared.
        $shared = $fragmentCount > 1;

        $fragment = $this->fragmentRepository->find($fragmentId);
        $fragment->getFragmentModel()->setMeta('shared', $shared);
        $fragment->getFragmentModel()->save();
    }
}
