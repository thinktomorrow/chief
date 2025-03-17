<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

use Thinktomorrow\Chief\Site\Urls\LinkStatus;

class SiteLink
{
    public function __construct(
        public readonly string $siteId,
        public readonly ?string $contextId,
        public readonly ?string $contextTitle,
        public readonly ?LinkUrl $url,
        public readonly ?LinkStatus $status,
    ) {
        //
    }

    //    public function toLivewire()
    //    {
    //        return [
    //            'siteId' => $this->siteId,
    //            'contextId' => $this->contextId,
    //            'contextTitle' => $this->contextTitle,
    //            'url' => $this->url->toArray(),
    //            'status' => $this->status->value,
    //        ];
    //    }
    //
    //    public static function fromLivewire($value)
    //    {
    //        return new static(
    //            siteId: $value['siteId'],
    //            contextId: $value['contextId'],
    //            contextTitle: $value['contextTitle'],
    //            url: LinkUrl::fromArray($value['url']),
    //            status: LinkStatus::from($value['status']),
    //        );
    //    }
}
