<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;

class FileEditDialog extends Component
{
    use ShowsAsDialog;

    public function render()
    {
        return view('chief-assets::file-edit-dialog');
    }
}
