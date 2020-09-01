<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Management\Nomadic;
use Thinktomorrow\Chief\Modules\ModuleManager;

class NomadicModuleManager extends ModuleManager
{
    use Nomadic;
}
