<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions;

use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\TreeResource;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Nestable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableTree;

class SelectOptions
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function getParentOptions(Nestable $model): array
    {
        $tree = $this->getTree($model);

        $modelChildrenIds = ($model && $model->getKey() && $nestedNode = $tree->find(fn ($node) => $node->getNodeId() == $model->getKey()))
            ? $nestedNode->pluckChildNodes('getNodeId')
            : [];

        return $tree
            ->remove(fn (Nestable $nestedNode) => ($nestedNode->getNodeId() == $model->getKey() || in_array($nestedNode->getNodeId(), $modelChildrenIds)))
            ->pluck($model->getKeyName(), fn (Nestable $nestedNode) => $nestedNode->getBreadCrumbLabel());
    }

    public function getTree(Nestable|string $model): NestableTree
    {
        $resource = $this->registry->findTreeResourceByModel(is_string($model) ? $model : $model::class);

        return NestableTree::fromIterable($resource->getTreeModels());
    }

    public function getOptions(string $modelClass, string $key = 'id'): array
    {
        return $this->getTree($modelClass)
            ->pluck($key, fn (Nestable $nestedNode) => $nestedNode->getBreadCrumbLabel());
    }
}
