<?php

namespace Thinktomorrow\Chief\Urls\UI\Livewire\Links;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Site\Visitable\BaseUrlSegment;
use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteDto;
use Thinktomorrow\Chief\Urls\App\Queries\GetBaseUrls;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

trait WithLinks
{
    public function getLinks(): Collection
    {
        $links = collect();

        $model = $this->getModel();
        $sites = $model instanceof HasAllowedSites
            ? ChiefSites::all()->filterByLocales($model->getAllowedSites())
            : ChiefSites::all();
        $activeRecords = $model->urls;

        /** @var UrlRecord $record */
        foreach ($activeRecords as $record) {
            $site = ChiefSites::all()->find($record->site);

            $status = LinkStatus::from($record->status);
            [$stateLabel, $stateVariant] = $status->influenceByModelState($model);

            $links->push(new LinkDto(
                $site->locale,
                SiteDto::fromConfig($site),
                new LinkUrl($record->id, $model->rawUrl($site->locale), $record->slug, BaseUrlSegment::strip($record->slug, $model->baseUrlSegment($site->locale))),
                $status,
                $stateLabel,
                $stateVariant,
                app(GetBaseUrls::class)->get($model),
            ));
        }

        /** @var ChiefSite $site */
        foreach ($sites as $site) {

            if ($links->contains(fn ($link) => $link->locale == $site->locale)) {
                continue;
            }

            $links->push(LinkDto::empty($model, $site->locale));
        }

        return $links;
    }

    private function getRedirects(): Collection
    {
        $model = $this->getModel();

        $redirects = app(UrlRepository::class)->getAllRedirects($model->modelReference());

        return $redirects->map(function (UrlRecord $record) use ($model) {
            $site = ChiefSites::all()->find($record->site);

            $status = LinkStatus::from($record->status);
            [$stateLabel, $stateVariant] = $status->influenceByModelState($model);

            return new LinkDto(
                $record->site,
                SiteDto::fromConfig($site),
                new LinkUrl($record->id, $model->resolveUrl($record->site, [$record->slug]), $record->slug, BaseUrlSegment::strip($record->slug, $model->baseUrlSegment($site->locale))),
                $status,
                $stateLabel,
                $stateVariant,
                [],
            );
        });
    }
}
