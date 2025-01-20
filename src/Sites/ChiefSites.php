<?php

namespace Thinktomorrow\Chief\Sites;

use Thinktomorrow\Chief\Forms\Fields\Locales\FieldLocales;

class ChiefSites
{
    /** @var ChiefSite[] */
    private array $sites;

    private function __construct(ChiefSite ...$sites)
    {
        if(empty($sites)) {
            throw new \InvalidArgumentException('At least one site should be provided.');
        }

        $this->sites = $sites;
    }

    public static function fromArray(array $sites): self
    {
        $chiefSites = [];

        foreach ($sites as $site) {
            $chiefSites[] = ChiefSite::fromArray($site);
        }

        return new static(...$chiefSites);
    }

    public function getLocales(): array
    {
        return array_map(fn (ChiefSite $site) => $site->locale, $this->sites);
    }

    /**
     * Grouped locales by fallback logic. E.g. ['nl' => ['nl', 'en'], 'fr' => ['fr', 'fr-be']]
     */
    public function getFieldLocales(): FieldLocales
    {
        $fieldLocales = new FieldLocales();

        foreach ($this->sites as $site) {
            $fieldLocales->add($site->locale, $site->fallbackLocale);
        }

        return $fieldLocales;
    }

    public function onlyActive(): self
    {
        return new static(...array_filter($this->sites, fn (ChiefSite $site) => $site->isActive));
    }

    public function getPrimaryLocale(): string
    {
        return $this->sites[0]?->locale;
    }

    public static function fieldLocales(): FieldLocales
    {
        static $locales;

        if ($locales) {
            return $locales;
        }

        return $locales = static::fromArray(config('chief.sites', []))->getFieldLocales();
    }

    public static function primaryFieldLocale(): FieldLocales
    {
        static $primaryFieldLocale;

        if ($primaryFieldLocale) {
            return $primaryFieldLocale;
        }

        $primaryLocale = static::fromArray(config('chief.sites', []))->getPrimaryLocale();

        return $primaryFieldLocale = !$primaryLocale ? new FieldLocales() : (new FieldLocales())->add($primaryLocale);;
    }

    public function toArray(): array
    {
        return array_map(fn (ChiefSite $site) => $site->toArray(), $this->sites);
    }

    //
    //    public static function activeSites(): array
    //    {
    //        return app(ChiefSites::class)->getActiveSites();
    //    }
    //
}
