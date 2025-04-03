<?php

namespace Thinktomorrow\Chief\Sites;

trait HasSiteContextsDefaults
{
    /**
     * This method is called on an Eloquent model to initialize the localized defaults.
     */
    public function initializeHasSiteContextsDefaults(): void
    {
        $this->mergeCasts(['site_contexts' => 'array']);
    }

    public function getSiteContexts(): array
    {
        return $this->site_contexts ?? [];
    }

    public function setSiteContexts(array $contextsByLocale): void
    {
        $this->site_contexts = $contextsByLocale;
    }

    public function getSiteContextFor(string $site): ?string
    {
        return $this->getSiteContexts()[$site] ?? null;
    }

    public function hasSiteContext(string $contextId): bool
    {
        return in_array($contextId, $this->getSiteContexts());
    }
}
