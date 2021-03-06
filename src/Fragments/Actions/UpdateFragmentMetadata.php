<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentAdded;
use Thinktomorrow\Chief\Fragments\Events\FragmentDuplicated;
use Thinktomorrow\Chief\Fragments\Events\SharedFragmentDetached;

class UpdateFragmentMetadata
{
    private FragmentRepository $fragmentRepository;
    private GetOwningModels $getOwningModels;

    public function __construct(FragmentRepository $fragmentRepository, GetOwningModels $getOwningModels)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->getOwningModels = $getOwningModels;
    }

    public function onFragmentAdded(FragmentAdded $event): void
    {
        $this->updateSharedState($event->fragmentModelId);
    }

    public function onFragmentDuplicated(FragmentDuplicated $event): void
    {
        $this->updateSharedState($event->fragmentModelId);
    }

    public function onSharedFragmentDetached(SharedFragmentDetached $event): void
    {
        $this->updateSharedState($event->fragmentModelId);
    }

    private function updateSharedState(int $fragmentModelId): void
    {
        $fragmentable = $this->fragmentRepository->find($fragmentModelId);

        $shared = count($this->getOwningModels->get($fragmentable->fragmentModel())) > 1;

        $fragmentable->fragmentModel()->setMeta('shared', $shared);
        $fragmentable->fragmentModel()->save();
    }
}
