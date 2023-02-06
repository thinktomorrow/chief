<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Model;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;

interface NestableRepository
{
    public function getTree(string $resourceKey): NestedTree;

    public function buildTree(Collection $models): NestedTree;
}
