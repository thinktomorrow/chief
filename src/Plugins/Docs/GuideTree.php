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
     * The top-level guides without children under "Aan de slag", followed by
     * a group per top-level section with the section's own page first. A
     * section without page or children has nothing to list and no group.
     *
     * @return list<GuideGroup>
     */
    public function groups(): array
    {
        $startingGuides = [];
        $sectionGroups = [];

        foreach ($this->roots() as $root) {
            $children = $this->children($root);

            if ($children === []) {
                if ($root->hasPage()) {
                    $startingGuides[] = $root;
                }

                continue;
            }

            $sectionGroups[] = new GuideGroup($root->title, $root, $root->hasPage() ? [$root, ...$children] : $children);
        }

        if ($startingGuides === []) {
            return $sectionGroups;
        }

        return [new GuideGroup('Aan de slag', null, $startingGuides), ...$sectionGroups];
    }

    /**
     * The guides with a page in the order the sidebar lists them: group by
     * group, each node followed by its descendants.
     *
     * @return list<GuideNode>
     */
    public function readingOrder(): array
    {
        $guides = [];

        foreach ($this->groups() as $group) {
            foreach ($group->nodes as $node) {
                $guides = [...$guides, ...$this->pagesFrom($node, $node !== $group->section)];
            }
        }

        return $guides;
    }

    /**
     * The given nodes that have a page, with each section without a page
     * replaced by the guides beneath it, as the docs list them as cards.
     *
     * @param  list<GuideNode>  $nodes
     * @return list<GuideNode>
     */
    public function guidesAmong(array $nodes): array
    {
        $guides = [];

        foreach ($nodes as $node) {
            $guides = [...$guides, ...($node->hasPage() ? [$node] : $this->guidesAmong($this->children($node)))];
        }

        return $guides;
    }

    public function previous(GuideNode $guide): ?GuideNode
    {
        return $this->neighbour($guide, -1);
    }

    public function next(GuideNode $guide): ?GuideNode
    {
        return $this->neighbour($guide, 1);
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
     * @return list<string> IDs from the top level down to the node itself.
     */
    public function pathIds(GuideNode $node): array
    {
        return [...array_map(fn (GuideNode $ancestor) => $ancestor->id, $this->ancestors($node)), $node->id];
    }

    /**
     * @return list<GuideNode> The node if it has a page, followed by the pages beneath it when descending.
     */
    private function pagesFrom(GuideNode $node, bool $descends): array
    {
        $pages = $node->hasPage() ? [$node] : [];

        if (! $descends) {
            return $pages;
        }

        foreach ($this->children($node) as $child) {
            $pages = [...$pages, ...$this->pagesFrom($child, true)];
        }

        return $pages;
    }

    private function neighbour(GuideNode $guide, int $offset): ?GuideNode
    {
        $guides = $this->readingOrder();
        $position = array_search($guide->id, array_map(fn (GuideNode $node) => $node->id, $guides), true);

        if ($position === false) {
            return null;
        }

        return $guides[$position + $offset] ?? null;
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
