<?php

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Model;

use Thinktomorrow\Chief\ManagedModels\Assistants\ModelDefaults;
use Thinktomorrow\Chief\ManagedModels\States\Archivable\Archivable;
use Thinktomorrow\Chief\ManagedModels\States\SimpleState\UsesSimpleState;

trait NestableModelDefault
{
    use ModelDefaults;
    use UsesSimpleState;
    use Archivable;
}
