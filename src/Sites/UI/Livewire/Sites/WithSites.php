<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\Sites;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;

trait WithSites
{
    private function getSites(): Collection
    {
        $model = ModelReference::fromString($this->modelReference)->instance();

        return ChiefSites::all()->filterByLocales($model->getSiteLocales())->toCollection()
            ->map(fn (ChiefSite $site) => SiteDto::fromConfig($site));
    }
}
