<?php
declare(strict_types = 1);

namespace Thinktomorrow\Chief\Menu\Tree;

use Illuminate\Database\DatabaseManager;
use Vine\Node;
use Vine\NodeCollection;
use Vine\Tree;
use Vine\TreeFactory;
use Thinktomorrow\Chief\Menu\MenuItem;

class MenuTreeRepository implements MenuTreeRepositoryContract
{
    /**
     * @var Tree[]
     */
    protected $trees = [];

    /**
     * @var DatabaseManager
     */
    protected $connection;

    /**
     * @var string
     */
    protected $locale;

    public function __construct(DatabaseManager $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param $locale
     * @return $this
     */
    public function locale($locale = null)
    {
        $this->locale = $locale;

        return $this;
    }

    public function get(): Tree
    {
        return $this->tree();
    }

    /**
     * Get all menu_items with their full path displayed
     * can be used in select field options on the admin
     *
     * @return array
     */
    public function getForSelectList(): array
    {
        return $this->tree()->pluck('id','fullPath');
    }

    public function find($id): Node
    {
        return $this->tree()->findByIndex($id) ?: new Node([]);
    }

    public function findMany(array $ids): NodeCollection
    {
        return $this->tree()->findManyByIndex($ids);
    }

    public function removeChildrenByIds(MenuItem $menu_item, $menu_item_ids)
    {
        $node = $this->find($menu->id);

        $children = $node->findMany('id', $menu_item_ids);

        $tree = clone $this->tree();

        return $tree->removeChildren($children);
    }

    public function getAncestorIds(array $menu_item_ids): array
    {
        $ids = [];

        foreach($menu_item_ids as $id)
        {
            $ids += $this->find($id)->pluckAncestors('id');
        }

        sort($ids);

        return array_values(array_unique($ids));
    }

    /**
     * @param array $menu_item_ids
     * @return mixed
     */
    public function getGrandChildrenIds(array $menu_item_ids)
    {
        $collection = $this->tree()->findMany('id',$menu_item_ids);

        $this->removeParents($menu_item_ids, $collection);

        return array_unique($collection->pluck('id'));
    }

    public function getGrandChildrenIdsBySlugs(array $menu_slugs)
    {
        $collection = $this->tree()->findMany('slug',$menu_slugs);

        $this->removeParents($menu_slugs, $collection, 'slug');

        return array_unique($collection->pluck('id'));
    }

    protected function tree(): Tree
    {
        $index = $this->locale ?: app()->getLocale();

        if( ! isset($this->trees[$index]))
        {
            $this->trees[$index] = (new TreeFactory())->create(
                new MenuTreeTransposer($this->getData())
            );
        }

        return $this->trees[$index];
    }

    protected function getData(): array
    {
        $locale = $this->locale ?: app()->getLocale();

        return $this->connection->table('menu_items')
                                ->join('menu_item_translations','menu_items.id','=','menu_item_translations.menu_item_id')
                                ->where('menu_item_translations.locale',$locale)
                                ->orWhere(function($query) use($locale){
                                    $query->where('menu_item_translations.locale','nl') // Default locale
                                            ->whereNotIn('menu_item_translations.menu_item_id', function ($q) use($locale){
                                                $q->select('menu_item_translations.menu_item_id')
                                                    ->from('menu_item_translations')
                                                    ->where('menu_item_translations.locale', $locale);
                                            });
                                })
                                ->select([
                                    'menu_items.id',
                                    'menu_items.parent_id', 
                                    'menu_items.type', 
                                    'menu_items.page_id', 
                                    'menu_items.collection_type', 
                                    'menu_item_translations.url',
                                    'menu_item_translations.label'
                                ])->get()->toArray();
    }

    /**
     * Only get the deepest child of a specific Menu tree in case more than one of same family are being
     * selected for querying on Menu. If a Menu is passed together with a Menu that has an
     * ancestor relationship with the former, the latter is removed so only the deepest selected children are returned.
     *
     * @param array $menu_item_ids
     * @param $collection
     * @param string $key identifier to check value against (id - slug)
     * @internal param array $menu_slugs
     */
    private function removeParents(array $menu_item_ids, NodeCollection $collection, $key = 'id')
    {
        foreach ($collection as $child)
        {
            $childIds = $child->pluck($key);
            array_shift($childIds);

            // If this parent has one of its children selected, only the child Menu will be used for the query
            if (!empty(array_intersect($childIds, $menu_item_ids)))
            {
                $collection->remove($child);
            }
        }
    }
}