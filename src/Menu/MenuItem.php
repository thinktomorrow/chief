<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu;

use Dimsav\Translatable\Translatable as BaseTranslatable;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Concerns\Morphable\GlobalMorphableScope;
use Thinktomorrow\Chief\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\Concerns\Translatable\Translatable;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableContract;
use Thinktomorrow\Chief\Pages\Page;
use Vine\Source as VineSource;
use Vine\Node;

class MenuItem extends Model implements TranslatableContract, VineSource
{
    const TYPE_INTERNAL = 'internal';
    const TYPE_CUSTOM   = 'custom';
    const TYPE_NOLINK   = 'nolink';

    use Translatable,
        BaseTranslatable;

    protected $translationModel      = MenuItemTranslation::class;
    protected $translationForeignKey = 'menu_item_id';
    protected $translatedAttributes  = [
        'label',
        'url',
    ];

    protected $with = ['page', 'translations'];

    public $timestamps = false;
    public $guarded    = [];

    public function ofType($type): bool
    {
        return $this->type == $type;
    }

    public function menuType()
    {
        return $this->menu_type;
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id')
            ->withoutGlobalScope(GlobalMorphableScope::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order', 'ASC');
    }

    public function siblings()
    {
        return static:: where('parent_id', $this->parent_id)->where('menu_type', $this->menuType())->where('id', '<>', $this->id)->orderBy('order', 'ASC')->get();
    }

    public function siblingsIncludingSelf()
    {
        return static:: where('parent_id', $this->parent_id)->where('menu_type', $this->menuType())->orderBy('order', 'ASC')->get();
    }

    /**
     * Auto generated for automatic collection group
     * @return bool
     */
    public function autoGenerated(): bool
    {
        return ($this->type == 'auto_generated');
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
    public function nodeEntries($type = 'main'): array
    {
        $items           = $this->where('menu_type', $type)->get();
        $collectionItems = collect([]);

        // Expose the collection items and populate them with the collection data
        foreach ($items as $k => $item) {
            // Fetch the collection items
            if ($item->collection_type) {
                $pages = Morphables::instance($item->collection_type)->getAllPublished();

                $pages->each(function (ActsAsMenuItem $page) use (&$collectionItems, $item) {
                    $collectionItems->push(MenuItem::make([
                        'id'             => 1000 . $item->id . $page->id,         // Unique integer identifier since model->id is automatically casted to int.
                        'label'          => $page->menuLabel(),
                        'url'            => self::composePageUrl($item, $page),
                        'parent_id'      => $item->id,
                        'type'           => 'auto_generated',
                        'menu_type'      => $item->menu_type,
                        'hidden_in_menu' => $page->hidden_in_menu,
                        'draft'          => $page->isDraft(),
                    ]));
                });
            }
            // Fetch the urls of the internal links
            if ($item->ofType(static::TYPE_INTERNAL) && $page = $item->page) {
                if ($page->isArchived()) {
                    unset($items[$k]);
                } else {
                    $item->url            = self::composePageUrl($item, $page);
                    $item->page_label     = $page->menuLabel();
                    $item->hidden_in_menu = $page->hidden_in_menu;
                    $item->draft          = $page->isDraft();
                    $items[$k]                   = $item;
                }
            }
        }

        return array_merge($items->all(), $collectionItems->all());
    }

    private static function composePageUrl(MenuItem $item, Page $page)
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
     */
    public function entry(Node $node)
    {
        return (object) [
            'id'             => $node->id,
            'type'           => $node->type,
            'label'          => $node->label,
            'page_label'     => $node->page_label,                       // Extra info when dealing with internal links
            'url'            => $node->url,
            'order'          => $node->order,
            'page_id'        => $node->page_id,
            'parent_id'      => $node->parent_id,
            'morph_key'      => $node->morph_key,
            'auto_generated' => $node->entry()->autoGenerated(),
            'hidden_in_menu' => $node->hidden_in_menu || $node->draft,
            'draft'          => $node->draft
        ];
    }
}
