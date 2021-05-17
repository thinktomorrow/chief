<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentRemovedFromContext;

class DeleteFragment
{
    private FragmentRepository $fragmentRepository;
    private GetOwningModels $getOwningModels;

    public function __construct(FragmentRepository $fragmentRepository, GetOwningModels $getOwningModels)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->getOwningModels = $getOwningModels;
    }

    public function handle(int $fragmentId): void
    {
        $fragmentModel = FragmentModel::find($fragmentId);

        $fragmentModel->delete();
        // Delete fragment

        // detach any asset relations
    }

    public function onFragmentRemovedFromContext(FragmentRemovedFromContext $event)
    {
        $fragmentable = $this->fragmentRepository->find($event->fragmentModelId);

        // By now the fragment is removed from the desired owning context. Here we check
        // that if the fragment is used by another context then leave britney alone!
        if (count($this->getOwningModels->get($fragmentable->fragmentModel())) > 0) {
            return;
        }

        $this->handle($event->fragmentModelId);
    }
}
