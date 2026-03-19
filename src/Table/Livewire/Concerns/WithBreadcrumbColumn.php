<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Illuminate\Contracts\Pagination\CursorPaginator as CursorPaginatorContract;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Resource\TreeResource;

trait WithBreadcrumbColumn
{
    // Ancestors per visible row for breadcrumb rendering
    private array $treeAncestorsByModelKey = [];

    private ?array $treeParentById = null;

    public function shouldShowTreeBreadcrumbColumn(): bool
    {
        if (! $this->allowsTreeBreadcrumbColumnSelection()) {
            return false;
        }

        if ($this->isReordering) {
            return false;
        }

        return ! $this->areResultsAsTree();
    }

    public function allowsTreeBreadcrumbColumnSelection(): bool
    {
        return $this->getTable()->shouldReturnResultsAsTree();
    }

    public function setTreeBreadcrumbsForResults(Collection|PaginatorContract|CursorPaginatorContract $results): void
    {
        $this->treeAncestorsByModelKey = [];

        if (! $this->shouldShowTreeBreadcrumbColumn() || ! $this->isTreeBreadcrumbColumnSelected()) {
            return;
        }

        $treeResource = $this->getTable()->getTreeResource() ?: $this->getTable()->getResourceReference()?->getResource();

        if (! $treeResource instanceof TreeResource) {
            return;
        }

        $rows = $results instanceof Collection ? $results : collect($results->items());
        $keyName = $this->getModelKeyName();
        $resultIds = $rows
            ->map(fn ($model) => $model->{$keyName} ?? null)
            ->filter()
            ->all();

        if (count($resultIds) === 0) {
            return;
        }

        $parentById = $this->getParentById($treeResource, $keyName);

        $ancestorIdsByResultId = [];
        $allAncestorIds = [];

        foreach ($resultIds as $resultId) {
            $ancestorIds = [];
            $parentId = $parentById[$resultId] ?? null;
            $guard = 0; // Protect against to large trees

            while ($parentId && $guard < 25) {
                $ancestorIds[] = $parentId;
                $parentId = $parentById[$parentId] ?? null;
                $guard++;
            }

            $ancestorIds = array_reverse($ancestorIds);

            $ancestorIdsByResultId[$resultId] = $ancestorIds;
            $allAncestorIds = array_merge($allAncestorIds, $ancestorIds);
        }

        $ancestorModelsById = $this->getAncestorModelsById($treeResource, $keyName, $allAncestorIds);

        foreach ($rows as $model) {
            $modelKey = (string) ($model->{$keyName} ?? '');

            if (! $modelKey) {
                continue;
            }

            $ancestorModels = collect($ancestorIdsByResultId[$model->{$keyName}] ?? [])
                ->map(fn ($ancestorId) => $ancestorModelsById->get($ancestorId))
                ->filter()
                ->values()
                ->all();

            $this->treeAncestorsByModelKey[$modelKey] = $ancestorModels;
        }
    }

    public function getTreeAncestors($model): array
    {
        $keyName = $this->getModelKeyName();
        $modelKey = (string) ($model->{$keyName} ?? '');

        if (! $modelKey) {
            return [];
        }

        return $this->treeAncestorsByModelKey[$modelKey] ?? [];
    }

    private function getParentById(TreeResource $treeResource, string $keyName): array
    {
        if ($this->treeParentById !== null) {
            return $this->treeParentById;
        }

        $treeModelIds = collect($treeResource->getTreeModelIds());
        $parentById = [];

        foreach ($treeModelIds as $node) {
            if (! isset($node->{$keyName})) {
                continue;
            }

            $parentById[$node->{$keyName}] = $node->parent_id ?? null;
        }

        $this->treeParentById = $parentById;

        return $parentById;
    }

    private function getAncestorModelsById(TreeResource $treeResource, string $keyName, array $allAncestorIds): Collection
    {
        $ancestorIds = array_values(array_unique($allAncestorIds));

        if (count($ancestorIds) === 0) {
            return collect();
        }

        if ($treeResource instanceof Resource) {
            $modelClass = $treeResource::modelClassName();

            return $modelClass::withoutGlobalScopes()
                ->whereIn($keyName, $ancestorIds)
                ->get()
                ->keyBy($keyName);
        }

        return $treeResource
            ->getTreeModels($ancestorIds)
            ->keyBy($keyName);
    }
}
