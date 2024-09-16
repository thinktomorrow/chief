<?php

namespace Thinktomorrow\Chief\Forms\Dialogs\Livewire;

use Livewire\Wireable;
use Thinktomorrow\Chief\Forms\Dialogs\Dialog;

interface DialogReference extends Wireable
{
    public function getDialog(): Dialog;
}
