<?php

namespace Thinktomorrow\Chief\Sites;

readonly class ChiefSite
{
    public function __construct(
        public string $locale,
        public ?string $fallbackLocale,
        public ?string $assetFallbackLocale,
        public bool $isActive,
        public bool $isPrimary,
        public string $name,
        public string $shortName,
        public ?string $url,
    ) {}

    public static function fromArray(array $site): self
    {
        if (! isset($site['locale'])) {
            throw new \InvalidArgumentException('Site array should contain at least an id and locale key.');
        }

        return new static(
            $site['locale'],
            $site['fallback_locale'] ?? null,
            $site['asset_fallback_locale'] ?? null,
            $site['active'] ?? false,
            $site['primary'] ?? false,
            $site['name'] ?? $site['locale'],
            $site['short_name'] ?? ($site['name'] ?? $site['locale']),
            $site['url'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'locale' => $this->locale,
            'fallback_locale' => $this->fallbackLocale,
            'asset_fallback_locale' => $this->assetFallbackLocale,
            'active' => $this->isActive,
            'primary' => $this->isPrimary,
            'name' => $this->name,
            'short_name' => $this->shortName,
            'url' => $this->url,
        ];
    }
}
