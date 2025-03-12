<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Queries\GetFragmentCount;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentAttached;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Events\FragmentDuplicated;

class UpdateFragmentMetadata
{
    private FragmentRepository $fragmentRepository;

    private GetFragmentCount $getFragmentCount;

    public function __construct(FragmentRepository $fragmentRepository, GetFragmentCount $getFragmentCount)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->getFragmentCount = $getFragmentCount;
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

        // We consider shared state only when the fragment belongs to two or more pages.
        // If the fragment belongs to multiple contexts of the same page, it is not considered shared.
        $shared = $this->getFragmentCount->get($fragmentId) > 1;

        $fragmentable = $this->fragmentRepository->find($fragmentId);
        $fragmentable->getFragmentModel()->setMeta('shared', $shared);
        $fragmentable->getFragmentModel()->save();
    }
}
