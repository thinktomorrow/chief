<?php
declare(strict_types = 1);

namespace Thinktomorrow\Chief\Menu;

use Dimsav\Translatable\Translatable as BaseTranslatable;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Common\Translatable\Translatable;
use Thinktomorrow\Chief\Common\Translatable\TranslatableContract;
use Thinktomorrow\Chief\Pages\Page;
use Vine\Source as VineSource;
use Vine\Node;

class MenuItem extends Model implements TranslatableContract, VineSource
{
    const TYPE_COLLECTION = 'collection';
    const TYPE_INTERNAL = 'internal';
    const TYPE_CUSTOM = 'custom';

    use Translatable,
        BaseTranslatable;

    protected $translationModel = MenuItemTranslation::class;
    protected $translationForeignKey = 'menu_item_id';
    protected $translatedAttributes = [
        'label', 'url'
    ];

    public $sortChildrenBy = 'order';

    public $timestamps = false;
    public $guarded = [];

    public function ofType($type): bool
    {
        return $this->type == $type;
    }

    /**
     * Full array of original data rows
     * These are the rows to be converted to the tree model
     *
     * @return array
     */
    public function nodeEntries(): array
    {
        $items = static::all();
        $collectionItems = collect([]);

        // Expose the collection items and populate them with the collection data
        foreach($items as $item) {
            if($item->ofType(static::TYPE_COLLECTION)) {

                // Get collection of pages
                $pages = Page::fromCollectionKey($item->collection_type)->all();

                $pages->each(function($page) use(&$collectionItems, $item){
                    $collectionItems->push(MenuItem::make([
                        'id'        => 'collection-'.$page->id,
                        'label'     => $page->title,
                        'url'       => $page->slug, // TODO: get url for page...
                        'parent_id' => $item->id,
                    ]));
                });
            }
        }

        return array_merge($items->all(), $collectionItems->all());
    }

    /**
     * Attribute key of the primary identifier of each row. e.g. 'id'
     *
     * @return string
     */
    public function nodeKeyIdentifier(): string
    {
        return 'id';
    }

    /**
     * Attribute key of the parent foreign identifier of each row. e.g. 'parent_id'
     *
     * @return string
     */
    public function nodeParentKeyIdentifier(): string
    {
        return 'parent_id';
    }

    public function entry(Node $node)
    {
        return [
            'id'                => $node->id,
            'label'             => $node->label,
            'order'             => $node->order,
            'page_id'           => $node->page_id,
            'parent_id'         => $node->parent_id,
            'hidden_in_menu'    => $node->hidden_in_menu
        ];
    }
}