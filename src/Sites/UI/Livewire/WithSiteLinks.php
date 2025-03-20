<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Urls\LinkStatus;
use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;

trait WithSiteLinks
{
    private function getSiteLinks(): Collection
    {
        $siteLinks = collect();

        $model = ModelReference::fromString($this->modelReference)->instance();
        $sites = ChiefSites::all()->filterByLocales($model->getSiteLocales());
        $contexts = app(ContextRepository::class)->getByOwner($model->modelReference());
        $activeRecords = $model->urls;

        /** @var ChiefSite $site */
        foreach ($sites as $site) {

            // Get current url for this site...
            $activeRecord = $activeRecords->filter(fn ($record) => $record->site == $site->locale)->first();

            if (! $activeRecord) {
                continue;
            }

            $siteLinks->push(new SiteLink(
                $site->locale,
                $activeRecord->context_id,
                $activeRecord->context_id ? $contexts->first(fn ($context) => $context->id == $activeRecord->context_id)?->title : null,
                SiteDto::fromConfig($site),
                new LinkUrl($activeRecord->id, $model->url($site->locale), $activeRecord->slug),
                LinkStatus::from($activeRecord->status),
            ));

        }

        return $siteLinks;
    }
}
