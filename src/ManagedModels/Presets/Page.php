<?php

namespace Thinktomorrow\Chief\ManagedModels\Presets;

use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\ViewableContract;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

interface Page extends ManagedModel, FragmentsOwner, Fragmentable, ProvidesUrl, ViewableContract, StatefulContract
{
    //
}
