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

    public $timestamps = false;
    public $guarded = [];

    public function ofType($type): bool
    {
        return $this->type == $type;
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
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
        foreach ($items as $k => $item) {

            // Fetch the collection items
            if ($item->ofType(static::TYPE_COLLECTION)) {
                $pages = Page::fromCollectionKey($item->collection_type)->all();

                $pages->each(function (ActsAsMenuItem $page) use (&$collectionItems, $item) {
                    $collectionItems->push(MenuItem::make([
                        'id'        => 'collection-'.$page->id,
                        'label'     => $page->menuLabel(),
                        'url'       => $page->menuUrl(),
                        'parent_id' => $item->id,
                    ]));
                });
            }

            // Fetch the urls of the internal links
            if ($item->ofType(static::TYPE_INTERNAL) && $page = $item->page) {
                $item->url = $page->menuUrl();
                $items[$k] = $item;
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

    /**
     * Convert entire models to condensed data arrays
     *
     * @param Node $node
     * @return array
     */
    public function entry(Node $node)
    {
        return (object)[
            'id'                => $node->id,
            'type'              => $node->type,
            'label'             => $node->label,
            'url'               => $node->url,
            'order'             => $node->order,
            'page_id'           => $node->page_id,
            'parent_id'         => $node->parent_id,
            'hidden_in_menu'    => $node->hidden_in_menu
        ];
    }
}
