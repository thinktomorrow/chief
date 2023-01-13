<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\ForwardNestableModelCalls;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestedNode;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Page\NestedNodeDefaults;
use Thinktomorrow\Vine\DefaultNode;

class NestedNodeStub extends DefaultNode implements NestedNode
{
    use NestedNodeDefaults;
    use ForwardNestableModelCalls;
}
