<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Page;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\ForwardNestableModelCalls;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestedNode;
use Thinktomorrow\Vine\DefaultNode;

final class PageNode extends DefaultNode implements NestedNode
{
    use NestedNodeDefaults;
    use ForwardNestableModelCalls;
}
