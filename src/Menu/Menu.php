<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Thinktomorrow\Chief\Sites\BelongsToSites;
use Thinktomorrow\Chief\Sites\BelongsToSitesDefaults;

class Menu extends Model implements BelongsToSites
{
    use BelongsToSitesDefaults;

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
}
