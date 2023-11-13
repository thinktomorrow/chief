<?php

namespace Thinktomorrow\Chief\Table\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class RowActionsDropdown extends Component
{
    use ShowsAsDialog;

    public $modelReference = null;

    public function render()
    {
        $model = $this->modelReference ? ModelReference::fromString($this->modelReference)->instance() : null;

        return view('chief-table::partials.row-actions-dropdown', [
            'model' => $model,
            'manager' => $model ? app(Registry::class)->findManagerByModel($model::class) : null,
        ]);
    }

    public function getListeners()
    {
        return [
            'openRowActionsDropdown' => 'open',
        ];
    }

    public function open($modelReference)
    {
        $this->modelReference = $modelReference;

        $this->isOpen = true;
    }
}
