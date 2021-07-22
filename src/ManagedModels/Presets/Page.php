<?php

namespace Thinktomorrow\Chief\ManagedModels\Presets;

use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\ManagedModels\States\WithPageState;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\ViewableContract;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

interface Page extends ManagedModel, FragmentsOwner, Visitable, ViewableContract, WithPageState, HasAsset
{
    //
}
