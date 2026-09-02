<?php

namespace Thinktomorrow\Chief\Forms\UI\Livewire\References;

use Livewire\Wireable;
use Thinktomorrow\Chief\Forms\Dialogs\Dialog;

interface DialogReference extends Wireable
{
    public function getDialog(): Dialog;
}
