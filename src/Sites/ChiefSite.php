<?php

namespace Thinktomorrow\Chief\Sites;

readonly class ChiefSite
{
    public function __construct(
        public string  $handle,
        public string  $locale,
        public ?string $fallbackLocale,
        public bool    $isActive,
        public bool    $isPrimary,
        public string  $name,
        public string  $shortName,
        public ?string  $url,
    ) {
    }

    public static function fromArray(array $site): self
    {
        if (! isset($site['handle'], $site['locale'])) {
            throw new \InvalidArgumentException('Site array should contain at least a handle and locale key.');
        }

        return new static(
            $site['handle'],
            $site['locale'],
            $site['fallback_locale'] ?? null,
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
            'handle' => $this->handle,
            'locale' => $this->locale,
            'fallback_locale' => $this->fallbackLocale,
            'active' => $this->isActive,
            'primary' => $this->isPrimary,
            'name' => $this->name,
            'short_name' => $this->shortName,
            'url' => $this->url,
        ];
    }
}
