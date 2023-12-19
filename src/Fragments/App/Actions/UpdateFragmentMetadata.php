<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Queries\GetOwningModels;
use Thinktomorrow\Chief\Fragments\Resource\Events\FragmentAttached;
use Thinktomorrow\Chief\Fragments\Resource\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Resource\Events\FragmentDuplicated;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentRepository;

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

    private function updateSharedState(string $fragmentId): void
    {
        if (! $this->fragmentRepository->exists($fragmentId)) {
            return;
        }

        $shared = $this->getOwningModels->getCount($fragmentId) > 1;

        $fragmentable = $this->fragmentRepository->find($fragmentId);
        $fragmentable->fragmentModel()->setMeta('shared', $shared);
        $fragmentable->fragmentModel()->save();
    }

    public function onFragmentDuplicated(FragmentDuplicated $event): void
    {
        $this->updateSharedState($event->fragmentId);
    }

    public function onFragmentDetached(FragmentDetached $event): void
    {
        $this->updateSharedState($event->fragmentId);
    }
}
