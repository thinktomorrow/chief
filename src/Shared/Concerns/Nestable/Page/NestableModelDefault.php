<?php

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Page;

use Thinktomorrow\Chief\ManagedModels\Models\ModelDefaults;
use Thinktomorrow\Chief\ManagedModels\States\Archivable\Archivable;
use Thinktomorrow\Chief\ManagedModels\States\SimpleState\UsesSimpleState;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\NestableDefault;

trait NestableModelDefault
{
    use ModelDefaults;
    use UsesSimpleState;
    use Archivable;
    use NestableDefault;
}
