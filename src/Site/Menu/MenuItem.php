<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;
use Thinktomorrow\Vine\Node;
use Thinktomorrow\Vine\NodeCollection;
use Thinktomorrow\Vine\NodeDefaults;

class MenuItem extends Model implements Node
{
    use NodeDefaults;
    use HasDynamicAttributes;

    public const TYPE_INTERNAL = 'internal';
    public const TYPE_CUSTOM = 'custom';
    public const TYPE_NOLINK = 'nolink';

    private ?string $locale = null;

    public $dynamicKeys = [
        'label', 'url', 'owner_label',
    ];

    public $timestamps = false;
    public $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->children = new NodeCollection();
    }

    public function getStatus(): MenuItemStatus
    {
        return MenuItemStatus::from($this->status);
    }

    public function setStatus(MenuItemStatus $status): void
    {
        $this->status = $status->value;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    private function getLocale(?string $locale = null): string
    {
        if ($locale) {
            return $locale;
        }

        return $this->locale ?: app()->getLocale();
    }

    public function isOffline(): bool
    {
        return $this->status != MenuItemStatus::online->value;
    }

    public function getLabel(?string $locale = null): ?string
    {
        // Prefer localized version over non-localized
        return $this->dynamic('label', $this->getLocale($locale), $this->label);
    }

    public function setLabel(string $label, string $locale): void
    {
        $this->setDynamic('label.' . $locale, $label);
    }

    public function getOwnerLabel(?string $locale = null): ?string
    {
        return $this->dynamic('owner_label', $this->getLocale($locale));
    }

    public function getAnyLabel(?string $locale = null): ?string
    {
        return $this->label ?: $this->getOwnerLabel($locale);
    }

    public function setOwnerLabel(string $ownerLabel, string $locale): void
    {
        $this->setDynamic('owner_label.' . $locale, $ownerLabel);
    }

    public function getUrl(?string $locale = null): ?string
    {
        // Prefer localized version over non-localized
        return $this->dynamic('url', $this->getLocale($locale), $this->url);
    }

    public function setUrl(?string $url, string $locale): void
    {
        $this->setDynamic('url.' . $locale, $url);
    }

    public static function getByOwner(string $ownerType, $ownerId): Collection
    {
        return static::where('owner_type', $ownerType)
            ->where('owner_id', $ownerId)
            ->get();
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

    // Used for ordering in admin
    public function siblingsIncludingSelf()
    {
        return static::where('parent_id', $this->parent_id)->where('menu_type', $this->menuType())->orderBy('order', 'ASC')->get();
    }

    public function dynamicLocales(): array
    {
        return \Thinktomorrow\Chief\Sites\ChiefSites::fieldLocales();
    }

    public function getId()
    {
        return $this->getNodeId();
    }

    public function getParentId()
    {
        return $this->getParentNodeId();
    }
}
