<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;
use Thinktomorrow\Vine\Node;

class MenuItem extends Model
{
    use HasDynamicAttributes;

    public const TYPE_INTERNAL = 'internal';
    public const TYPE_CUSTOM = 'custom';
    public const TYPE_NOLINK = 'nolink';

    public $dynamicKeys = [
        'label', 'url', 'owner_label',
    ];

    public $timestamps = false;
    public $guarded = [];

    public function getLabel(string $locale): ?string
    {
        // Prefer localized version over non-localized
        return $this->dynamic('label', $locale, $this->label);
    }

    public function getUrl(string $locale): ?string
    {
        // Prefer localized version over non-localized
        return $this->dynamic('url', $locale, $this->url);
    }

    public function getStatus(): MenuItemStatus
    {
        return MenuItemStatus::from($this->status);
    }

    public function setStatus(MenuItemStatus $status): void
    {
        $this->status = $status->value;
    }

    public function getAdminUrlLabel(string $locale): string
    {
        if (self::TYPE_INTERNAL == $this->type) {
            return $this->dynamic('owner_label', $locale);
        }

        if (! $url = $this->getUrl($locale)) {
            return 'geen link';
        }

        return $url;
    }

    public function setOwnerLabel(string $locale, string $ownerLabel): void
    {
        $this->setDynamic('owner_label.' . $locale, $ownerLabel);
    }

    public function setUrl(string $locale, ?string $url): void
    {
        $this->setDynamic('url.' . $locale, $url);
    }

//    public static function allIncludingEmptyEntries(string $key, string $locale)
//    {
//        return static::where('menu_type', $key)
//            ->get()
//            ->map(function (self $menuItem) use ($locale) {
    ////                if (!$menuItem->getLabel($locale)) {
    ////                    $menuItem->setDynamic('label.'.$locale, '-');
    ////                }
//
//                return $menuItem;
//            })
//        ;
//    }

    public static function getByOwner(string $ownerType, $ownerId): Collection
    {
        return static::where('owner_type', $ownerType)
            ->where('owner_id', $ownerId)
            ->get()
            ;
    }

    public function ofType($type): bool
    {
        return $this->type == $type;
    }

    public function menuType()
    {
        return $this->menu_type;
    }

    public function owner(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('owner', 'owner_type', 'owner_id')
                    ->withoutGlobalScopes();
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order', 'ASC');
    }

    public function siblings()
    {
        return static::where('parent_id', $this->parent_id)->where('menu_type', $this->menuType())->where('id', '<>', $this->id)->orderBy('order', 'ASC')->get();
    }

    public function siblingsIncludingSelf()
    {
        return static::where('parent_id', $this->parent_id)->where('menu_type', $this->menuType())->orderBy('order', 'ASC')->get();
    }

    public function scopeOnlyGrandParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function dynamicLocales(): array
    {
        return config('chief.locales', []);
    }

//    public function url($locale = null)
//    {
//        if (! $locale) {
//            $locale = app()->getLocale();
//        }
//
//        if ($this->ofType(static::TYPE_INTERNAL) && $owner = $this->owner) {
//            return $owner->url($locale);
//        }
//
//        return $this->dynamic('url', $locale);
//    }

    /*
     * Convert entire models to condensed data arrays
     *
     * @param Node $node
     *
     * @return \stdClass
     */
//    public function entry(Node $node): \stdClass
//    {
//        // TODO: place in menusource...
//        // There we use the projected links.
//        return (object)[
//            'id' => $node->getNodeEntry('id'),
//            'type' => $node->getNodeEntry('type'),
//            'label' => $node->getNodeEntry('label'),
//            'page_label' => $node->getNodeEntry('page_label'), // Extra info when dealing with internal links
//            'url' => $node->getNodeEntry()->url(),
//            'order' => $node->getNodeEntry('order'),
//            'owner_type' => $node->getNodeEntry('owner_type'),
//            'owner_id' => $node->getNodeEntry('owner_id'),
//            'parent_id' => $node->getNodeEntry('parent_id'),
//            'morph_key' => $node->getNodeEntry('morph_key'),
//            'hidden_in_menu' => $node->getNodeEntry('hidden_in_menu'),
//            'draft' => $node->getNodeEntry('draft'),
//        ];
//    }
}
