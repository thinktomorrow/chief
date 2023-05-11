<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestableNodeDefaults;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedNode;
use Thinktomorrow\Vine\DefaultNode;

class NestedNodeStub extends DefaultNode implements NestedNode
{
    use NestableNodeDefaults;
}
