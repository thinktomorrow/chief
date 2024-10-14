<?php

namespace Thinktomorrow\Chief\Table\Livewire;

use Thinktomorrow\Chief\Managers\Exceptions\MissingResourceRegistration;
use Thinktomorrow\Chief\Resource\TreeResource;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableTree;

class TreeModels
{
    /**
     * @return array [ancestors, models]
     * @throws MissingResourceRegistration
     */
    public function compose(TreeResource $resource, array $ids, int $offset, int $limit, string $keyName = 'id'): array
    {
        // Get the entire tree structure but only the ids to reduce memory load
        $treeIds = NestableTree::fromIterable($resource->getTreeModelIds());

        // Reduce the tree to only the models that are expected by the current filtering / sorting.
        $treeIds = (new NestableTree($treeIds->shake(fn ($node) => in_array($node->getNodeId(), $ids))->flatten()->all()))->all();

        // Slice the models for the current page
        $treeIds = collect($treeIds)->slice($offset, $limit)->values();

        // Also get the ancestors of first model so this can be shown as a tree path reference
        $ancestorTreeIds = collect(isset($treeIds[0]) ? $treeIds[0]->getAncestorNodes() : []);

        // Fetch the entire models
        $allModels = $resource->getTreeModels([
            ...($modelIds = $treeIds->map(fn ($node) => $node->getNodeId())->all()),
            ...($ancestorIds = $ancestorTreeIds->map(fn ($node) => $node->getNodeId())->all()),
        ]);

        // Add indent to models based on the tree depth
        $models = $allModels
            ->filter(fn ($model) => in_array($model->id, $modelIds))
            ->map(function ($model) use ($treeIds, $keyName) {
                $model->indent = $treeIds->first(fn ($node) => $node->getNodeId() == $model->{$keyName})->getNodeDepth();

                return $model;
            });

        $ancestors = $allModels
            ->filter(fn ($model) => in_array($model->id, $ancestorIds))
            ->map(function ($model) use ($ancestorTreeIds, $keyName) {
                $model->indent = $ancestorTreeIds->first(fn ($node) => $node->getNodeId() == $model->{$keyName})->getNodeDepth();

                return $model;
            });

        return [$ancestors, $models];
    }
}
