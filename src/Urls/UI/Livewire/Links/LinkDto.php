<?php

namespace Thinktomorrow\Chief\Urls\UI\Livewire\Links;

use Livewire\Wireable;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteDto;
use Thinktomorrow\Chief\Urls\App\Queries\GetBaseUrls;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;

class LinkDto implements Wireable
{
    public function __construct(
        public readonly string $locale,
        public readonly ?string $contextId,
        public readonly ?string $contextTitle,
        public readonly SiteDto $site,
        public readonly ?LinkUrl $url,
        public LinkStatus $status,
        public readonly string $stateLabel,
        public readonly string $stateVariant,
        public readonly array $baseUrls = [], // Per locale
    ) {
        //
    }

    public static function empty(Visitable $model, string $locale): self
    {
        $site = ChiefSites::all()->find($locale);

        [$stateLabel, $stateVariant] = LinkStatus::offline->influenceByModelState($model);

        return new static(
            locale: $locale,
            contextId: null,
            contextTitle: null,
            site: SiteDto::fromConfig($site),
            url: null,
            status: LinkStatus::offline,
            stateLabel: $stateLabel,
            stateVariant: $stateVariant,
            baseUrls: app(GetBaseUrls::class)->get($model)
        );
    }

    public function toLivewire()
    {
        return [
            'locale' => $this->locale,
            'contextId' => $this->contextId,
            'contextTitle' => $this->contextTitle,
            'site' => $this->site->toLivewire(),
            'url' => $this->url?->toArray(),
            'status' => $this->status->value,
            'stateLabel' => $this->stateLabel,
            'stateVariant' => $this->stateVariant,
            'baseUrls' => $this->baseUrls,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            locale: $value['locale'],
            contextId: $value['contextId'],
            contextTitle: $value['contextTitle'],
            site: SiteDto::fromLivewire($value['site']),
            url: ($value['url'] ? LinkUrl::fromArray($value['url']) : null),
            status: LinkStatus::from($value['status']),
            stateLabel: $value['stateLabel'] ?? '',
            stateVariant: $value['stateVariant'] ?? '',
            baseUrls: $value['baseUrls'] ?? [],
        );
    }

    public function changeStatus(LinkStatus $status): void
    {
        $this->status = $status;
    }
}
