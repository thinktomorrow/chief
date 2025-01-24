<?php

namespace Thinktomorrow\Chief\Sites;

use Thinktomorrow\Chief\Forms\Fields\Locales\FieldLocales;

class ChiefSites
{
    /** @var ChiefSite[] */
    private array $sites;

    /** @var ChiefSite[] */
    private static ?array $cachedSites = null;

    private function __construct(ChiefSite ...$sites)
    {
        $this->assertAllIdsAreUnique($sites);

        $this->sites = $sites;
    }

    public static function fromConfig(): self
    {
        $sites = self::fromArray(config('chief.sites', []));
        $sites->assertAtLeastOneSiteIsAdded();

        return $sites;
    }

    public function get(): array
    {
        return $this->sites;
    }

    public function getLocales(): array
    {
        return array_map(fn (ChiefSite $site) => $site->locale, $this->sites);
    }

    private function onlyActive(): self
    {
        return new self(...array_filter($this->sites, fn (ChiefSite $site) => $site->isActive));
    }

    public function filterByIds(array $siteIds): self
    {
        return new self(...array_filter($this->sites, fn (ChiefSite $site) => in_array($site->id, $siteIds)));
    }

    public static function all(): array
    {
        if (self::$cachedSites) {
            return self::$cachedSites;
        }

        return self::$cachedSites = self::fromConfig()->get();
    }

    public function getPrimaryLocale(): ?string
    {
        foreach($this->sites as $site) {
            if($site->isPrimary) {
                return $site->locale;
            }
        }

        if(empty($this->sites)) {
            return null;
        }

        // By default, we assume the first site is primary
        return $this->sites[0]->locale;
    }

    public function toArray(): array
    {
        return array_map(fn (ChiefSite $site) => $site->toArray(), $this->sites);
    }

    public static function clearCache(): void
    {
        self::$cachedSites = null;
    }

    private static function fromArray(array $sites): self
    {
        $chiefSites = [];

        foreach ($sites as $site) {
            $chiefSites[] = ChiefSite::fromArray($site);
        }

        return new static(...$chiefSites);
    }

    private function assertAtLeastOneSiteIsAdded(): void
    {
        if(empty($this->sites)) {
            throw new \InvalidArgumentException('At least one site should be provided.');
        }
    }

    private function assertAllIdsAreUnique(array $sites): void
    {
        $ids = array_map(fn (ChiefSite $site) => $site->id, $sites);

        if(count($ids) !== count(array_unique($ids))) {
            throw new \InvalidArgumentException('Site ids should be unique.');
        }
    }
}
