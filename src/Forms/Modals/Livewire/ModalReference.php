<?php

namespace Thinktomorrow\Chief\Forms\Modals\Livewire;

use Livewire\Wireable;
use Thinktomorrow\Chief\Forms\Modals\Modal;

interface ModalReference extends Wireable
{
    public function getModal(): Modal;
}
