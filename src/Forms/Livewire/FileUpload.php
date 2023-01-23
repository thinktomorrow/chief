<?php

namespace Thinktomorrow\Chief\Forms\Livewire;

use Livewire\Component;

class FileUpload extends Component
{
    public string $key;
    public string $name;
    public bool $multiple = false;


    public function render()
    {
        return view('chief-form::livewire.file-upload', [
            //
        ]);
    }
}
