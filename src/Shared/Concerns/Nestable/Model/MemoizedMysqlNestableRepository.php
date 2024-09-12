<?php

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Model;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;

class MemoizedMysqlNestableRepository implements NestableRepository
{
    /** @var NestedTree[] */
    private static array $trees = [];

    /** @var NestedTree[] */
    private static array $treeIds = [];

    private MysqlNestableRepository $nestableRepository;

    public function __construct(MysqlNestableRepository $nestableRepository)
    {
        $this->nestableRepository = $nestableRepository;
    }

    public function getTree(string $resourceKey): NestedTree
    {
        if (isset(static::$trees[$resourceKey])) {
            return static::$trees[$resourceKey];
        }

        return static::$trees[$resourceKey] = $this->nestableRepository->getTree($resourceKey);
    }

    public function getTreeIds(string $resourceKey): NestedTree
    {
        if (isset(static::$treeIds[$resourceKey])) {
            return static::$treeIds[$resourceKey];
        }

        return static::$treeIds[$resourceKey] = $this->nestableRepository->getTreeIds($resourceKey);
    }

    public function buildTree(Collection $models): NestedTree
    {
        return $this->nestableRepository->buildTree($models);
    }
}
