<?php

namespace Thinktomorrow\Chief\ManagedModels\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class ListOptions extends Component
{
    public $modelReference;

    public function mount(string $modelReference)
    {
        $this->modelReference = $modelReference;
    }

    public function render()
    {
        // Add toggle (open handler on each three-dots icon)
        // Show loading inside dropdown...
        // We want ONE component in DOM and load the proper values on open.
        // showsAsDialog -> dropdown
        // on open -> set proper modelReference
        // Make sure to set archive modal and such...

        $model = ModelReference::fromString($this->modelReference)->instance();
        $manager = app(Registry::class)->findManagerByModel($model::class);

        return view('chief::manager.livewire.list-options', [
            'model' => $model,
            'manager' => $manager,
        ]);
    }
}
