<?php

namespace Thinktomorrow\Chief\ManagedModels\Presets;

use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\ViewableContract;
use Thinktomorrow\Chief\ManagedModels\States\PageState\WithPageState;

interface Page extends ReferableModel, FragmentsOwner, Visitable, ViewableContract, WithPageState, HasAsset
{
}
