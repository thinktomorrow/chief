<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Form;

use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\TreeResource;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\NestableRepository;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedNode;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;

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

        $modelChildrenIds = ($model && $model->getKey() && $nestedNode = $tree->find(fn ($node) => $node->getId() == $model->getKey()))
            ? $nestedNode->pluckChildNodes('getId')
            : [];

        return $tree
            ->remove(fn (NestedNode $nestedNode) => ($nestedNode->getId() == $model->getKey() || in_array($nestedNode->getId(), $modelChildrenIds)))
            ->pluck($model->getKeyName(), fn (NestedNode $nestedNode) => $nestedNode->getBreadCrumbLabel());
    }

    public function getTree(Nestable|string $model): NestedTree
    {
        /** @var TreeResource $resource */
        $resource = $this->registry->findResourceByModel(is_string($model) ? $model : $model::class);

        // TODO: use resource instead
        return NestedTree::fromIterable($resource->getAllTreeModels());
//        return app(NestableRepository::class)->getTree($resource::resourceKey());
    }

    public function getOptions(string $modelClass, string $key = 'id'): array
    {
        return $this->getTree($modelClass)
            ->pluck($key, fn (NestedNode $nestedNode) => $nestedNode->getBreadCrumbLabel());
    }
}
