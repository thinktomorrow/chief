<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

/**
 * All guide nodes of every doc source, merged into one hierarchy.
 */
final class GuideTree
{
    /**
     * @param  array<string, GuideNode>  $nodes  Keyed by node ID.
     * @param  array<string, null|string>  $parentIds  Parent ID per node ID, null for the top level.
     * @param  array<string, list<string>>  $childIds  Sorted child IDs per parent ID, '' for the top level.
     */
    public function __construct(
        private readonly array $nodes,
        private readonly array $parentIds,
        private readonly array $childIds,
    ) {}

    public function find(string $id): ?GuideNode
    {
        return $this->nodes[$id] ?? null;
    }

    /** @return list<GuideNode> */
    public function roots(): array
    {
        return $this->nodesById($this->childIds[''] ?? []);
    }

    /** @return list<GuideNode> */
    public function children(GuideNode $node): array
    {
        return $this->nodesById($this->childIds[$node->id] ?? []);
    }

    /**
     * @return list<GuideNode> From the top level down to the node's parent.
     */
    public function ancestors(GuideNode $node): array
    {
        $ancestors = [];
        $parentId = $this->parentIds[$node->id] ?? null;

        while ($parentId !== null) {
            array_unshift($ancestors, $this->nodes[$parentId]);
            $parentId = $this->parentIds[$parentId] ?? null;
        }

        return $ancestors;
    }

    /**
     * @param  list<string>  $ids
     * @return list<GuideNode>
     */
    private function nodesById(array $ids): array
    {
        return array_map(fn (string $id) => $this->nodes[$id], $ids);
    }
}
