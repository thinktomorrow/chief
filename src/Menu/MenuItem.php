<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu;

use Dimsav\Translatable\Translatable as BaseTranslatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Common\Collections\GlobalCollectionScope;
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
    const TYPE_NOLINK = 'nolink';

    use Translatable,
        BaseTranslatable;

    protected $translationModel = MenuItemTranslation::class;
    protected $translationForeignKey = 'menu_item_id';
    protected $translatedAttributes = [
        'label',
        'url',
    ];
    protected $with = ['page', 'translations'];

    public $timestamps = false;
    public $guarded = [];

    public function ofType($type): bool
    {
        return $this->type == $type;
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id')
            ->withoutGlobalScope(GlobalCollectionScope::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id');
    }

    public function scopeOnlyGrandParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function url()
    {
        if ($this->ofType(static::TYPE_INTERNAL) && $page = $this->page) {
            return $page->menuUrl();
        }

        return $this->url;
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

                $pages = Page::fromCollectionKey($item->collection_type)->getAllPublished();

                $pages->reject(function ($page) {
                    return $page->hidden_in_menu == true;
                })->each(function (ActsAsMenuItem $page) use (&$collectionItems, $item) {
                    $collectionItems->push(MenuItem::make([
                        'id'         => 'collection-' . $page->id,
                        'label'      => $page->menuLabel(),
                        'url'        => $this->composePageUrl($item, $page),
                        'parent_id'  => $item->id,
                    ]));
                });
            }

            // Fetch the urls of the internal links
            if ($item->ofType(static::TYPE_INTERNAL) && $page = $item->page) {
                if ($page->hidden_in_menu == true) {
                    unset($items[$k]);
                } else {
                    $item->url = $this->composePageUrl($item, $page);
                    $item->page_label = $page->menuLabel();
                    $items[$k] = $item;
                }
            }
        }
        return array_merge($items->all(), $collectionItems->all());
    }

    private function composePageUrl(MenuItem $item, Page $page)
    {
        return $page->menuUrl();
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
            'id'             => $node->id,
            'type'           => $node->type,
            'label'          => $node->label,
            'page_label'     => $node->page_label, // Extra info when dealing with internal links
            'url'            => $node->url,
            'order'          => $node->order,
            'page_id'        => $node->page_id,
            'parent_id'      => $node->parent_id,
        ];
    }
}
