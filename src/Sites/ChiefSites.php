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

    public function find(string $locale): ChiefSite
    {
        foreach ($this->sites as $site) {
            if ($site->locale === $locale) {
                return $site;
            }
        }

        throw new \InvalidArgumentException('Site with id ['.$locale.'] not found in chief config.');
    }

    public function getLocales(): array
    {
        return array_map(fn (ChiefSite $site) => $site->locale, $this->sites);
    }

    public function getNames(): array
    {
        return $this->toCollection()->mapWithKeys(fn ($site) => [$site->locale => $site->name])->toArray();
    }

    public function getShortNames(): array
    {
        return $this->toCollection()->mapWithKeys(fn ($site) => [$site->locale => $site->shortName])->toArray();
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

    public function filterByLocales(array $locales): self
    {
        return new self(...array_filter($this->sites, fn (ChiefSite $site) => in_array($site->locale, $locales)));
    }

    public function rejectByLocales(array $locales): self
    {
        return new self(...array_filter($this->sites, fn (ChiefSite $site) => ! in_array($site->locale, $locales)));
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
        $locales = array_map(fn (ChiefSite $site) => $site->locale, $sites);

        if (count($locales) !== count(array_unique($locales))) {
            throw new \InvalidArgumentException('Site locales should be unique.');
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
