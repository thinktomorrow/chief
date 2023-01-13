<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

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

    // Used for ordering in admin
    public function siblingsIncludingSelf()
    {
        return static::where('parent_id', $this->parent_id)->where('menu_type', $this->menuType())->orderBy('order', 'ASC')->get();
    }

    public function dynamicLocales(): array
    {
        return config('chief.locales', []);
    }
}
