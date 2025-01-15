<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Queries\GetOwningModels;
use Thinktomorrow\Chief\Fragments\Events\FragmentAttached;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Events\FragmentDuplicated;
use Thinktomorrow\Chief\Fragments\Repositories\FragmentRepository;

class UpdateFragmentMetadata
{
    private FragmentRepository $fragmentRepository;
    private GetOwningModels $getOwningModels;

    public function __construct(FragmentRepository $fragmentRepository, GetOwningModels $getOwningModels)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->getOwningModels = $getOwningModels;
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
        $shared = $this->getOwningModels->getCount($fragmentId) > 1;

        $fragmentable = $this->fragmentRepository->find($fragmentId);
        $fragmentable->fragmentModel()->setMeta('shared', $shared);
        $fragmentable->fragmentModel()->save();
    }
}
