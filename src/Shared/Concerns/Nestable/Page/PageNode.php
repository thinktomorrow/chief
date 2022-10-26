<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Page;

use Thinktomorrow\Vine\DefaultNode;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestedNode;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\ForwardNestableModelCalls;

final class PageNode extends DefaultNode implements NestedNode
{
    use NestedNodeDefaults;
    use ForwardNestableModelCalls;
}

