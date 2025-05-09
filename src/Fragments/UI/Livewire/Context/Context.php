<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\Context;

use Livewire\Component;
use Thinktomorrow\Chief\Forms\UI\Livewire\WithMemoizedModel;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\UI\Livewire\_partials\WithFragments;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class Context extends Component
{
    use WithFragments;
    use WithMemoizedModel;

    public ContextDto $context;

    public ModelReference $modelReference;

    public string $scopedLocale;

    public function mount(ContextDto $context, string $scopedLocale, ContextOwner&ReferableModel $model)
    {
        $this->context = $context;
        $this->modelReference = $model->modelReference();
        $this->setMemoizedModel($model);
        $this->scopedLocale = $scopedLocale;

        $this->refreshFragments();

    }

    public function getListeners()
    {
        return array_merge(
            $this->getListenersWithFragments(),
            [
            ]
        );
    }

    public function render()
    {
        return view('chief-fragments::livewire.context');
    }
}
