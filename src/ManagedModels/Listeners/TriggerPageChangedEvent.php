<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Listeners;

use Thinktomorrow\Chief\Forms\Events\FormUpdated;
use Thinktomorrow\Chief\Fragments\App\Queries\GetOwningModels;
use Thinktomorrow\Chief\Fragments\Resource\Events\FragmentAdded;
use Thinktomorrow\Chief\Fragments\Resource\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Resource\Events\FragmentDuplicated;
use Thinktomorrow\Chief\Fragments\Resource\Events\FragmentsReordered;
use Thinktomorrow\Chief\Fragments\Resource\Events\FragmentUpdated;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentRepository;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelDeleted;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUrlUpdated;
use Thinktomorrow\Chief\ManagedModels\Events\PageChanged;

class TriggerPageChangedEvent
{
    private FragmentRepository $fragmentRepository;
    private GetOwningModels $getOwningModels;

    public function __construct(FragmentRepository $fragmentRepository, GetOwningModels $getOwningModels)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->getOwningModels = $getOwningModels;
    }

    public function onManagedModelUrlUpdated(ManagedModelUrlUpdated $e): void
    {
        event(new PageChanged($e->modelReference));
    }

    public function onManagedModelUpdated(ManagedModelUpdated $e): void
    {
        event(new PageChanged($e->modelReference));
    }

    public function onManagedModelDeleted(ManagedModelDeleted $e): void
    {
        event(new PageChanged($e->modelReference));
    }

    public function onFragmentUpdated(FragmentUpdated $e): void
    {
        $this->handleFragmentChange($e->fragmentId);
    }

    private function handleFragmentChange($fragmentId): void
    {
        $models = $this->getOwningModels->get($fragmentId);

        foreach ($models as $model) {
            if ($model instanceof FragmentModel) {
                continue;
            }

            event(new PageChanged($model['model']->modelReference()));
        }
    }

    public function onFragmentsReordered(FragmentsReordered $e): void
    {
        $model = ContextModel::find($e->contextId)->getOwner();

        if ($model instanceof FragmentModel) {
            return;
        }

        event(new PageChanged($model->modelReference()));
    }

    public function onFragmentDetached(FragmentDetached $e): void
    {
        $this->handleFragmentChange($e->fragmentId);
    }

    public function onFragmentAdded(FragmentAdded $e): void
    {
        $this->handleFragmentChange($e->fragmentId);
    }

    public function onFragmentDuplicated(FragmentDuplicated $e): void
    {
        $this->handleFragmentChange($e->fragmentId);
    }

    public function onFormUpdated(FormUpdated $e): void
    {
        event(new PageChanged($e->modelReference));
    }
}
