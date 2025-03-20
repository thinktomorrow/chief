<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteLinks;

use Livewire\Wireable;
use Thinktomorrow\Chief\Site\Urls\LinkStatus;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\UI\Livewire\Sites\SiteDto;

class SiteLink implements Wireable
{
    public function __construct(
        public readonly string $locale,
        public readonly ?string $contextId,
        public readonly ?string $contextTitle,
        public readonly SiteDto $site,
        public readonly ?LinkUrl $url,
        public readonly LinkStatus $status,
    ) {
        //
    }

    public static function empty(string $locale): self
    {
        $site = ChiefSites::all()->find($locale);

        return new static(
            locale: $locale,
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
            'locale' => $this->locale,
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
            locale: $value['locale'],
            contextId: $value['contextId'],
            contextTitle: $value['contextTitle'],
            site: SiteDto::fromLivewire($value['site']),
            url: ($value['url'] ? LinkUrl::fromArray($value['url']) : null),
            status: LinkStatus::from($value['status']),
        );
    }
}
