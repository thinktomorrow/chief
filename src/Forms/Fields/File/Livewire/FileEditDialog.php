<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Forms\Fields\File\Livewire\Traits\ShowsAsDialog;

class FileEditDialog extends Component
{
    use ShowsAsDialog;

    public function render()
    {
        return view('chief-form::fields.file.file-edit-dialog');
    }
}
