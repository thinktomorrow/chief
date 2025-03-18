<?php

namespace Thinktomorrow\Chief\Sites;

use ArrayIterator;
use Traversable;

class ChiefSites implements \Countable, \IteratorAggregate
{
    /** @var ChiefSite[] */
    private array $sites;

    private static ?ChiefSites $cachedSites = null;

    private function __construct(ChiefSite ...$sites)
    {
        $this->sites = $sites;

        $this->assertAllIdsAreUnique($sites);
    }

    public static function fromConfig(): self
    {
        $self = self::fromArray(config('chief.sites', []));
        $self->assertAtLeastOneSiteIsAdded();

        return $self;
    }

    private static function fromArray(array $sites): self
    {
        $chiefSites = [];

        foreach ($sites as $site) {
            $chiefSites[] = ChiefSite::fromArray($site);
        }

        return new static(...$chiefSites);
    }

    public function get(): array
    {
        return $this->sites;
    }

    public function find(string $siteId): ChiefSite
    {
        foreach ($this->sites as $site) {
            if ($site->id === $siteId) {
                return $site;
            }
        }

        throw new \InvalidArgumentException('Site with id ['.$siteId.'] not found in chief config.');
    }

    public function getLocales(): array
    {
        return array_map(fn (ChiefSite $site) => $site->locale, $this->sites);
    }

    public function toArray(): array
    {
        return array_map(fn (ChiefSite $site) => $site->toArray(), $this->sites);
    }

    public function toCollection(): \Illuminate\Support\Collection
    {
        return collect($this->sites);
    }

    private function onlyActive(): self
    {
        return new self(...array_filter($this->sites, fn (ChiefSite $site) => $site->isActive));
    }

    public function filterByIds(array $siteIds): self
    {
        return new self(...array_filter($this->sites, fn (ChiefSite $site) => in_array($site->id, $siteIds)));
    }

    public function rejectByIds(array $siteIds): self
    {
        return new self(...array_filter($this->sites, fn (ChiefSite $site) => ! in_array($site->id, $siteIds)));
    }

    public static function all(): self
    {
        if (self::$cachedSites) {
            return self::$cachedSites;
        }

        return self::$cachedSites = self::fromConfig();
    }

    public function getPrimaryLocale(): ?string
    {
        return $this->getPrimarySite()->locale;
    }

    private function getPrimarySite(): ChiefSite
    {
        foreach ($this->sites as $site) {
            if ($site->isPrimary) {
                return $site;
            }
        }

        // By default, we assume the first site is primary
        return $this->sites[0];
    }

    public static function clearCache(): void
    {
        self::$cachedSites = null;
    }

    private function assertAtLeastOneSiteIsAdded(): void
    {
        if (empty($this->sites)) {
            throw new \InvalidArgumentException('At least one site should be provided.');
        }
    }

    private function assertAllIdsAreUnique(array $sites): void
    {
        $ids = array_map(fn (ChiefSite $site) => $site->id, $sites);

        if (count($ids) !== count(array_unique($ids))) {
            throw new \InvalidArgumentException('Site ids should be unique.');
        }
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->sites);
    }

    public function count(): int
    {
        return count($this->sites);
    }
}
