<?php

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\ManagedModels\States\Archivable\Archivable;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\Publishable;
use Thinktomorrow\Chief\ManagedModels\States\UsesPageState;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidingUrl;

trait PageDefaults
{
    use FragmentableDefaults;
    use Viewable;
    use ProvidingUrl;
    use OwningFragments;

    use UsesPageState;
    use Publishable;
    use Archivable;
}
