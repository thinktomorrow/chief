<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

use Livewire\Wireable;
use Thinktomorrow\Chief\Sites\ChiefSite;

class SiteDto implements Wireable
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $shortName,
        public readonly string $url,
        public readonly string $locale,
        public readonly ?string $fallbackLocale,
        public readonly bool $isActive,
        public readonly bool $isPrimary,
    ) {
        //
    }

    public static function fromConfig(ChiefSite $site): self
    {
        return new static(
            $site->id,
            $site->name,
            $site->shortName,
            $site->url,
            $site->locale,
            $site->fallbackLocale,
            $site->isActive,
            $site->isPrimary,
        );
    }

    public function toLivewire()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'shortName' => $this->shortName,
            'url' => $this->url,
            'locale' => $this->locale,
            'fallbackLocale' => $this->fallbackLocale,
            'isActive' => $this->isActive,
            'isPrimary' => $this->isPrimary,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            id: $value['id'],
            name: $value['name'],
            shortName: $value['shortName'],
            url: $value['url'],
            locale: $value['locale'],
            fallbackLocale: $value['fallbackLocale'],
            isActive: $value['isActive'],
            isPrimary: $value['isPrimary'],
        );
    }
}
