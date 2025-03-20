<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

use Livewire\Wireable;
use Thinktomorrow\Chief\Sites\ChiefSite;

class SiteDto implements Wireable
{
    public function __construct(
        public readonly string $locale,
        public readonly ?string $fallbackLocale,
        public readonly string $name,
        public readonly string $shortName,
        public readonly string $url,
        public readonly bool $isActive,
        public readonly bool $isPrimary,
    ) {
        //
    }

    public static function fromConfig(ChiefSite $site): self
    {
        return new static(
            $site->locale,
            $site->fallbackLocale,
            $site->name,
            $site->shortName,
            $site->url,
            $site->isActive,
            $site->isPrimary,
        );
    }

    public function toLivewire()
    {
        return [
            'locale' => $this->locale,
            'fallbackLocale' => $this->fallbackLocale,
            'name' => $this->name,
            'shortName' => $this->shortName,
            'url' => $this->url,
            'isActive' => $this->isActive,
            'isPrimary' => $this->isPrimary,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            locale: $value['locale'],
            fallbackLocale: $value['fallbackLocale'],
            name: $value['name'],
            shortName: $value['shortName'],
            url: $value['url'],
            isActive: $value['isActive'],
            isPrimary: $value['isPrimary'],
        );
    }
}
