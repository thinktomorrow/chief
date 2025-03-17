<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Visitable;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Symfony\Component\HttpFoundation\Response;

interface Visitable
{
    /**
     * Full url to be used in frontend navigation
     */
    public function url(?string $site = null): string;

    /**
     * Check whether this model can be viewed
     * by the current visitor or not.
     */
    public function isVisitable(): bool;

    /**
     * The base category uri segment for this type of model.
     * e.g. /news/, /events/.
     */
    public function baseUrlSegment(?string $site = null): string;

    /**
     * Create the full url for this given resource
     *
     * @param  array|string|null  $parameters
     */
    public function resolveUrl(?string $site = null, $parameters = null): string;

    public function response(): Response;

    /** All current urls, excluding redirects */
    public function urls(): HasMany;

    /** All urls, including redirects */
    public function allUrls(): HasMany;
}
