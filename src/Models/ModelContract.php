<?php

namespace Thinktomorrow\Chief\Models;

use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

interface ModelContract extends HasAsset, ReferableModel, StatefulContract {}
