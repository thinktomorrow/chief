<?php

namespace Thinktomorrow\Chief\TableNew\Livewire;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\NestableRepository;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;

class TreeModels {

//    private Registry $registry;
//
//    public function __construct(Registry $registry)
//    {
//        $this->registry = $registry;
//    }

    public function create(string $resourceKey, array $ids, string $keyName = 'id'): NestedTree
    {
        $tree = $this->getTree($resourceKey);

        return new NestedTree($tree->shake(fn($node) => in_array($node->getNodeId(), $ids))->flatten()->all());
        return $tree->findMany($keyName, $ids);
    }

//    // Category, Page, ...
//    public function add(Model $model): Model
//    {
//        $model->setAttribute('tree', $this->getTreeData($model));
//
//        return $model;
//    }
//
//    private function getTreeData(Model $model): array
//    {
//        $treeModel = $this->findTreeModel($model);
//
//        return [
//            'breadCrumbs' => $treeModel->getModel()->getBreadCrumbs(),
//            'depth' => $treeModel->getNodeDepth(),
//        ];
//    }
//
//    private function findTreeModel(Model $model): NestedNode
//    {
//        $tree = $this->getTree($this->registry->findResourceByModel($model::class)::resourceKey());
//
//        return $tree->find($model->getKeyName(), $model->getKey());
//    }

    private function getTree(string $resourceKey)
    {
        return app(NestableRepository::class)->getTree($resourceKey);
    }
}
