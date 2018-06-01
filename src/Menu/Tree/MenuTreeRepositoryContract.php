<?php
namespace Thinktomorrow\Chief\Menu\Tree;

use Vine\Node;
use Vine\NodeCollection;
use Vine\Tree;
use Thinktomorrow\Chief\Menu\MenuItem;

interface MenuTreeRepositoryContract
{
    public function locale($locale = null);

    public function get(): Tree;

    public function find($id): Node;

    public function findMany(array $ids): NodeCollection;

    public function removeChildrenByIds(MenuItem $menu_item, $menu_item_ids);

    public function getAncestorIds(array $menu_item_ids): array;

    public function getGrandChildrenIds(array $menu_item_ids);

    public function getGrandChildrenIdsBySlugs(array $menu_item_slugs);
}