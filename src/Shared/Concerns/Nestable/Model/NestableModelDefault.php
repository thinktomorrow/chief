<?php

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Model;

use Thinktomorrow\Chief\ManagedModels\Models\ModelDefaults;
use Thinktomorrow\Chief\ManagedModels\States\Archivable\Archivable;
use Thinktomorrow\Chief\ManagedModels\States\SimpleState\UsesSimpleState;

trait NestableModelDefault
{
    use Archivable;
    use ModelDefaults;
    use UsesSimpleState;
}
