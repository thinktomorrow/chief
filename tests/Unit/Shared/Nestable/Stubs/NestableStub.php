<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs;

use Concerns\Nestable\Nestable;
use Concerns\Nestable\NestableDefault;
use Thinktomorrow\Vine\DefaultNode;

class NestableStub extends DefaultNode implements Nestable
{
    use NestableDefault;
}
