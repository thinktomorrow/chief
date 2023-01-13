<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestedNode;

interface NestableRepository
{
    public function findNestableById(string|int $nestableId): ?NestedNode;

    public function getTree(): NestedTree;
}
