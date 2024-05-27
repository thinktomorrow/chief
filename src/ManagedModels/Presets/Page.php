<?php

namespace Thinktomorrow\Chief\ManagedModels\Presets;

use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\ViewableContract;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\MultiSiteable;

/**
 * @deprecated use Thinktomorrow\Chief\Pages\Page instead
 */
interface Page extends ReferableModel, MultiSiteable, ContextOwner, FragmentsOwner, Visitable, ViewableContract, StatefulContract, HasAsset
{
}
