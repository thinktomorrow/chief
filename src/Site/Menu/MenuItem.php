<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu;

use Vine\Node;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

class MenuItem extends Model
{
    use HasDynamicAttributes;

    const TYPE_INTERNAL = 'internal';
    const TYPE_CUSTOM = 'custom';
    const TYPE_NOLINK = 'nolink';

    public $dynamicKeys = [
        'label', 'url',
    ];

    public $timestamps = false;
    public $guarded = [];

    public function dynamicLocales(): array
    {
        return config('chief.locales', []);
    }

    public function ofType($type): bool
    {
        return $this->type == $type;
    }

    public function menuType()
    {
        return $this->menu_type;
    }

    public function owner()
    {
        return $this->morphTo('owner', 'owner_type', 'owner_id');
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

    public function scopeOnlyGrandParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function url($locale = null)
    {
        if(!$locale) $locale = app()->getLocale();

        if ($this->ofType(static::TYPE_INTERNAL) && $owner = $this->owner) {
            return $owner->url($locale);
        }

        return $this->dynamic('url', $locale);
    }

    /**
     * Convert entire models to condensed data arrays
     *
     * @param Node $node
     */
    public function entry(Node $node)
    {
        return (object)[
            'id'             => $node->id,
            'type'           => $node->type,
            'label'          => $node->label,
            'page_label'     => $node->page_label,                       // Extra info when dealing with internal links
            'url'            => $node->url(),
            'order'          => $node->order,
            'owner_type'     => $node->owner_type,
            'owner_id'       => $node->owner_id,
            'parent_id'      => $node->parent_id,
            'morph_key'      => $node->morph_key,
            'hidden_in_menu' => $node->hidden_in_menu,
            'draft'          => $node->draft,
        ];
    }
}
