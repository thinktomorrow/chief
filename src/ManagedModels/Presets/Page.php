<?php

namespace Thinktomorrow\Chief\ManagedModels\Presets;

use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\ViewableContract;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\BelongsToSites;

interface Page extends BelongsToSites, ContextOwner, FragmentsOwner, HasAsset, ReferableModel, StatefulContract, ViewableContract, Visitable {}
