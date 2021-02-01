<?php

namespace Thinktomorrow\Chief\ManagedModels\Presets;

use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\ViewableContract;

interface Fragment extends ManagedModel, Fragmentable, ViewableContract
{
    //
}
