<?php

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Nestable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableTree;

class MemoizedSelectOptions extends SelectOptions
{
    private static array $memoizedTrees = [];

    public function getTree(Nestable|string $model, array $eagerLoading = []): NestableTree
    {
        $modelClass = is_string($model) ? $model : $model::class;

        if (! isset(self::$memoizedTrees[$modelClass])) {
            self::$memoizedTrees[$modelClass] = parent::getTree($model, $eagerLoading);
        }

        return self::$memoizedTrees[$modelClass];
    }
}
