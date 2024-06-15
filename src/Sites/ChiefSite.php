<?php

namespace Thinktomorrow\Chief\Sites;

class ChiefSite
{
    public function __construct(
        public readonly string $locale,
        public readonly string $name,
        public readonly string $shortName,
        public readonly string $url,
        public readonly bool $isActive,
        public readonly string $isoCode,
        public readonly ?string $defaultLocale,
    ) {
    }
}
