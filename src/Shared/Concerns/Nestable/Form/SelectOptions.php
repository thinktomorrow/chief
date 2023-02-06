<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Form;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\PageResource;
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

    public function getTree(Nestable $model): NestedTree
    {
        $resource = $this->registry->findResourceByModel($model::class);

        return app(NestableRepository::class)->getTree($resource::resourceKey());
    }
}
