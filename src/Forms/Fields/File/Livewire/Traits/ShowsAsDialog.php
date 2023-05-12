<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\Livewire\Traits;

trait ShowsAsDialog
{
    public $isOpen = false;

    public function toggleDialog()
    {
        if ($this->isOpen) {
            $this->close();
        } else {
            $this->open();
        }
    }

    public function open()
    {
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
    }
}
