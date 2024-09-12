<?php

namespace Thinktomorrow\Chief\TableNew\Livewire;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Managers\Exceptions\MissingResourceRegistration;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\TreeResource;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\NestableRepository;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;

class TreeModels
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return array [ancestors, models]
     * @throws MissingResourceRegistration
     */
    public function get(string $resourceKey, array $ids, int $offset, int $limit, string $keyName = 'id'): array
    {
        $resource = $this->registry->resource($resourceKey);

        return $this->create($resource, $ids, $offset, $limit, $keyName);
    }

    private function create(TreeResource $resource, array $ids, int $offset, int $limit, string $keyName = 'id'): array
    {
        // Get the entire tree structure but only the ids to reduce memory load
        $treeIds = NestedTree::fromIterable($resource->getTreeModelIds());

        // Reduce the tree to only the models that are expected by the current filtering / sorting.
        $treeIds = (new NestedTree($treeIds->shake(fn ($node) => in_array($node->getNodeId(), $ids))->flatten()->all()))->all();

        // Paginate the models
        // TODO: reduce this to only the models that are needed...
        // Can we fetch only the ID's and afterwards the full models?

        // Slice the models for the current page
        $treeIds = collect($treeIds)->slice($offset, $limit)->values();

        // Also get the ancestors of first model so this can be shown as a tree path reference
        $ancestorTreeIds = collect(isset($treeIds[0]) ? $treeIds[0]->getAncestorNodes() : []);

        // Fetch the entire models
        $allModels = $resource->getTreeModelsByIds([
            ...($modelIds = $treeIds->map(fn ($node) => $node->getNodeId())->all()),
            ...($ancestorIds = $ancestorTreeIds->map(fn ($node) => $node->getNodeId())->all()),
        ]);

        // Add indent to models based on the tree depth
        $models = $allModels
            ->filter(fn ($model) => in_array($model->id, $modelIds))
            ->map(function ($model) use ($treeIds) {
                $model->indent = $treeIds->first(fn ($node) => $node->getNodeId() == $model->id)->getNodeDepth();

                return $model;
            });

        $ancestors = $allModels
            ->filter(fn ($model) => in_array($model->id, $ancestorIds))
            ->map(function ($model) use ($ancestorTreeIds) {
                $model->indent = $ancestorTreeIds->first(fn ($node) => $node->getNodeId() == $model->id)->getNodeDepth();

                return $model;
            });

        return [$ancestors, $models];
    }

    private function convertNodesToModels($nodes): array
    {
        return array_map(function ($node) {

            $model = $node->getNodeEntry();
            $model->indent = $node->getNodeDepth();

            return $model;
        }, $nodes);
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

    private function getModelsById(TreeResource $resource, array $ids)
    {
        return $resource->getTreeModelsByIds($ids);
        //    {
        //        return app(NestableRepository::class)->getTree($resourceKey);
    }

    private function getTreeIds(string $resourceKey)
    {
        // 1. Get Resource - this should be a TreeResource...
        // 2. call the getTreeModelIds method on the resource
        // 3. return the result

        return app(NestableRepository::class)->getTreeIds($resourceKey);
    }
}
