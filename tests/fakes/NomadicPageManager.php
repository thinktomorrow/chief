<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Management\Nomadic;

class NomadicPageManager extends PageManager
{
    use Nomadic;
}
