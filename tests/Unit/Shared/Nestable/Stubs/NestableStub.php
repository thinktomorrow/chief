<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Nestable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableDefault;
use Thinktomorrow\Vine\DefaultNode;

class NestableStub extends DefaultNode implements Nestable
{
    use NestableDefault;
}
