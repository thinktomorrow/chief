<?php

namespace Thinktomorrow\Chief\Table\Livewire;

use Thinktomorrow\Chief\Managers\Exceptions\MissingResourceRegistration;
use Thinktomorrow\Chief\Resource\TreeResource;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableTree;
use Thinktomorrow\Vine\DefaultNode;

class TreeModels
{
    /**
     * @return array [ancestors, models]
     *
     * @throws MissingResourceRegistration
     */
    public function compose(TreeResource $resource, array $ids, int $offset, int $limit, string $keyName = 'id'): array
    {
        // Get the entire tree structure but only the ids to reduce memory load
        $treeModelIds = NestableTree::fromIterable($resource->getTreeModelIds(), function ($model) use ($keyName) {
            return new DefaultNode($model, new NestableTree, $keyName, 'parent_id');
        });

        // Reduce the tree to only the models that are expected by the current filtering / sorting.
        $treeIds = collect(
            (new NestableTree($treeModelIds->prune(fn ($node) => in_array($node->getNodeId(), $ids))->flatten()->all()))->all()
        )->slice($offset, $limit)->values();

        // Also get the ancestors of first model so this can be shown as a tree path reference
        $ancestorTreeIds = collect(isset($treeIds[0]) ? $treeModelIds->find(fn ($node) => $node->getNodeId() == $treeIds[0]->getNodeId())->getAncestorNodes() : []);

        // Fetch the entire models
        $allModels = $resource->getTreeModels([
            ...($modelIds = $treeIds->map(fn ($node) => $node->getNodeId())->all()),
            ...($ancestorIds = $ancestorTreeIds->map(fn ($node) => $node->getNodeId())->all()),
        ]);

        // Add indent to models based on the tree depth
        $models = $allModels
            ->filter(fn ($model) => in_array($model->{$keyName}, $modelIds))
            ->map(function ($model) use ($treeModelIds, $keyName) {
                $model->indent = $treeModelIds->find(fn ($node) => $node->getNodeId() == $model->{$keyName})->getNodeDepth();

                return $model;
            })->values();

        $ancestors = $allModels
            ->filter(fn ($model) => in_array($model->{$keyName}, $ancestorIds))
            ->map(function ($model) use ($ancestorTreeIds, $keyName) {
                $model->indent = $ancestorTreeIds->first(fn ($node) => $node->getNodeId() == $model->{$keyName})->getNodeDepth();

                return $model;
            });

        return [$ancestors, $models];
    }
}
