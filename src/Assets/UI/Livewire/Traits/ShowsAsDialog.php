<?php

namespace Thinktomorrow\Chief\Assets\UI\Livewire\Traits;

trait ShowsAsDialog
{
    public $isOpen = false;

    public function open()
    {
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
    }
}
