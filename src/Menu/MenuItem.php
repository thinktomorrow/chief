<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Resource\TreeResource;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableTree;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Chief\Sites\HasSiteLocales;
use Thinktomorrow\Chief\Sites\HasSiteLocalesDefaults;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;
use Thinktomorrow\Vine\Node;
use Thinktomorrow\Vine\NodeCollection;
use Thinktomorrow\Vine\NodeDefaults;

class MenuItem extends Model implements HasSiteLocales, Node, ReferableModel, TreeResource
{
    use HasDynamicAttributes;
    use HasSiteLocalesDefaults;
    use NodeDefaults;
    use ReferableModelDefault;

    public $dynamicKeys = [
        'label', 'url', 'owner_label',
    ];

    public $timestamps = false;

    public $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->children = new NodeCollection;
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
        $this->setDynamic('label.'.$locale, $label);
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
        $this->setDynamic('owner_label.'.$locale, $ownerLabel);
    }

    public function getUrl(?string $locale = null): ?string
    {
        // Prefer localized version over non-localized
        return $this->dynamic('url', $this->getLocale($locale), $this->url);
    }

    public function setUrl(?string $url, string $locale): void
    {
        $this->setDynamic('url.'.$locale, $url);
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
        return static::where('parent_id', $this->parent_id)->where('menu_id', $this->menu_id)->where('id', '<>', $this->id)->orderBy('order', 'ASC')->get();
    }

    // Used for ordering in admin
    public function siblingsIncludingSelf()
    {
        return static::where('parent_id', $this->parent_id)->where('menu_id', $this->menu_id)->orderBy('order', 'ASC')->get();
    }

    public function getDynamicLocales(): array
    {
        return \Thinktomorrow\Chief\Sites\Locales\ChiefLocales::locales();
    }

    public function getId()
    {
        return $this->getNodeId();
    }

    public function getParentId()
    {
        return $this->getParentNodeId();
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function getTreeModelIds(): array
    {
        return DB::table($this->getTable())
            ->orderBy('order')
            ->select(['id', 'parent_id'])
            ->get()
            ->all();
    }

    public function getTreeModels(?array $ids = null): Collection
    {
        $models = static::withoutGlobalScopes()
            ->orderBy('order')
            ->when($ids, fn ($query) => $query->whereIn('id', $ids))
            ->get();

        // Sort by parent
        return collect(NestableTree::fromIterable($models)->sort('order')->flatten());
    }
}
