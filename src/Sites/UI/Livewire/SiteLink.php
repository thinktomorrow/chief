<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

use Livewire\Wireable;
use Thinktomorrow\Chief\Site\Urls\LinkStatus;
use Thinktomorrow\Chief\Sites\ChiefSites;

class SiteLink implements Wireable
{
    public function __construct(
        public readonly string $siteId,
        public readonly ?string $contextId,
        public readonly ?string $contextTitle,
        public readonly SiteDto $site,
        public readonly ?LinkUrl $url,
        public readonly LinkStatus $status,
    ) {
        //
    }

    public static function empty(string $siteId): self
    {
        $site = ChiefSites::all()->find($siteId);

        return new static(
            siteId: $siteId,
            contextId: null,
            contextTitle: null,
            site: SiteDto::fromConfig($site),
            url: null,
            status: LinkStatus::offline,
        );
    }

    public function toLivewire()
    {
        return [
            'siteId' => $this->siteId,
            'contextId' => $this->contextId,
            'contextTitle' => $this->contextTitle,
            'site' => $this->site->toLivewire(),
            'url' => $this->url?->toArray(),
            'status' => $this->status->value,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            siteId: $value['siteId'],
            contextId: $value['contextId'],
            contextTitle: $value['contextTitle'],
            site: SiteDto::fromLivewire($value['site']),
            url: ($value['url'] ? LinkUrl::fromArray($value['url']) : null),
            status: LinkStatus::from($value['status']),
        );
    }
}
