<?php

namespace Thinktomorrow\Chief\Urls\UI\Livewire\Links;

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

trait WithLinks
{
    private ?Visitable $model = null;

    private function getModel()
    {
        if ($this->model) {
            return $this->model;
        }

        return $this->model = ModelReference::fromString($this->modelReference)->instance();
    }

    private function getLinks(): Collection
    {
        $links = collect();

        $model = $this->getModel();
        $sites = ChiefSites::all()->filterByLocales($model->getAllowedSites());
        $contexts = app(ContextRepository::class)->getByOwner($model->modelReference());
        $activeRecords = $model->urls;

        /** @var UrlRecord $record */
        foreach ($activeRecords as $record) {

            $site = ChiefSites::all()->find($record->site);

            $status = LinkStatus::from($record->status);
            [$stateLabel, $stateVariant] = $status->influenceByModelState($model);

            $links->push(new LinkDto(
                $site->locale,
                $record->context_id,
                $record->context_id ? $contexts->first(fn ($context) => $context->id == $record->context_id)?->title : null,
                SiteDto::fromConfig($site),
                new LinkUrl($record->id, $model->url($site->locale), $record->slug, BaseUrlSegment::strip($record->slug, $model->baseUrlSegment($site->locale))),
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
                null,
                null,
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
