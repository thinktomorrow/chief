<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteLinks;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Visitable\BaseUrlSegment;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteDto;
use Thinktomorrow\Chief\Urls\App\Queries\GetBaseUrls;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

trait WithSiteLinks
{
    private ?Visitable $model = null;

    private function getModel()
    {
        if ($this->model) {
            return $this->model;
        }

        return $this->model = ModelReference::fromString($this->modelReference)->instance();
    }

    private function getSiteLinks(): Collection
    {
        $siteLinks = collect();

        $model = $this->getModel();
        $sites = ChiefSites::all()->filterByLocales($model->getAllowedSites());
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
                new LinkUrl($activeRecord->id, $model->url($site->locale), $activeRecord->slug, BaseUrlSegment::strip($activeRecord->slug, $model->baseUrlSegment($site->locale))),
                LinkStatus::from($activeRecord->status),
                app(GetBaseUrls::class)->get($model),
            ));

        }

        return $siteLinks;
    }

    private function getRedirects(): Collection
    {
        $model = $this->getModel();

        $redirects = app(UrlRepository::class)->getAllRedirects($model->modelReference());

        return $redirects->map(function (UrlRecord $record) use ($model) {
            $site = ChiefSites::all()->find($record->site);

            return new SiteLink(
                $record->site,
                null,
                null,
                SiteDto::fromConfig($site),
                new LinkUrl($record->id, $model->resolveUrl($record->site, [$record->slug]), $record->slug, BaseUrlSegment::strip($record->slug, $model->baseUrlSegment($site->locale))),
                LinkStatus::from($record->status),
                [],
            );
        });
    }
}
