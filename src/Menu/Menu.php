<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Thinktomorrow\Chief\Sites\HasActiveSites;
use Thinktomorrow\Chief\Sites\HasActiveSitesDefaults;

class Menu extends Model implements HasActiveSites
{
    use HasActiveSitesDefaults;

    public $guarded = [];

    public function getTitle(): string
    {
        return $this->title ?: $this->getTypeLabel();
    }

    public function getTypeLabel(): string
    {
        return MenuType::find($this->type)->getLabel();
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    public static function findDefault(string $type): static
    {
        return static::where('type', $type)->orderBy('order')->firstOrFail();
    }
}
