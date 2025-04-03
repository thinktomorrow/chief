<?php

namespace Thinktomorrow\Chief\Sites;

/**
 * Model that keeps reference to which contexts it is active per site locale
 * Note that this only applies to models without managed site links.
 *
 * Site links already take care of active contexts
 */
interface HasSiteContexts
{
    public function getSiteContexts(): array;

    public function setSiteContexts(array $contextsByLocale): void;

    public function getSiteContextFor(string $site): ?string;

    public function hasSiteContext(string $contextId): bool;
}
