<?php

namespace Thinktomorrow\Chief\TableNew\Livewire;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\NestableRepository;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;

class TreeModels
{

    //    private Registry $registry;
    //
    //    public function __construct(Registry $registry)
    //    {
    //        $this->registry = $registry;
    //    }

    public function create(string $resourceKey, array $ids, int $offset, int $limit, string $keyName = 'id'): array
    {
        $tree = $this->getTree($resourceKey);

        $models = (new NestedTree($tree->shake(fn ($node) => in_array($node->getNodeId(), $ids))->flatten()->all()))->all();

        // Paginate the models
        $models = array_slice($models, $offset, $limit);

        // Mark the ancestor models to the result if they are not present in the current page
        if(count($models) > 0) {
            $models = array_merge($models[0]->getAncestorNodes()->each(function ($node) {
                $node->getNodeEntry()->setAttribute('isAncestorRow', true);

                return $node;
            })->all(), $models);
        }

        // Return the models instead of the nodes and add any node data to each model
        return array_map(function ($node) {

            $model = $node->getModel();
            $model->indent = $node->getNodeDepth();

            // This is a bit of a hack to make sure the ancestor rows are always present in the result
            $model->isAncestorRow ??= false;

            return $model;
        }, $models);
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
