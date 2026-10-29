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
     * @param  array<string, true>  $withheldIds  IDs of the nodes left out of this visible part of the tree.
     */
    public function __construct(
        private readonly array $nodes,
        private readonly array $parentIds,
        private readonly array $childIds,
        private readonly array $withheldIds = [],
    ) {}

    /**
     * The part of the tree that an admin with the given roles may see, without
     * the hidden nodes. The walk only descends into visible nodes, so a
     * section's restriction or hiding covers its whole subtree and a child
     * can only narrow it.
     *
     * @param  list<string>  $roleNames
     * @param  list<string>  $hiddenIds
     */
    public function visibleTo(array $roleNames, array $hiddenIds): self
    {
        $isHidden = array_fill_keys($hiddenIds, true);
        $visibleChildIds = [];
        $pendingParentIds = [''];

        while ($pendingParentIds !== []) {
            $parentId = array_shift($pendingParentIds);

            foreach ($this->childIds[$parentId] ?? [] as $id) {
                if (! isset($isHidden[$id]) && $this->nodes[$id]->isVisibleTo($roleNames)) {
                    $visibleChildIds[$parentId][] = $id;
                    $pendingParentIds[] = $id;
                }
            }
        }

        $isVisible = array_fill_keys(array_merge(...array_values($visibleChildIds)), true);

        return new self(
            array_intersect_key($this->nodes, $isVisible),
            array_intersect_key($this->parentIds, $isVisible),
            $visibleChildIds,
            $this->withheldIds + array_fill_keys(array_keys(array_diff_key($this->nodes, $isVisible)), true),
        );
    }

    public function find(string $id): ?GuideNode
    {
        return $this->nodes[$id] ?? null;
    }

    /**
     * Whether the node exists but was left out of this visible part of the tree,
     * because it is hidden or restricted to other roles.
     */
    public function isWithheld(string $id): bool
    {
        return isset($this->withheldIds[$id]);
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
