<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Visitable;

interface Visitable
{
    /**
     * Full url to be used in frontend navigation
     *
     * @param null|string $locale
     * @return string
     */
    public function url(string $locale = null): string;

    /**
     * Check whether this model can be viewed
     * by the current visitor or not.
     *
     * @return bool
     */
    public function isVisitable(): bool;

    /**
     * The base category uri segment for this type of model.
     * e.g. /news/, /events/.
     *
     * @param null|string $locale
     * @return string
     */
    public function baseUrlSegment(string $locale = null): string;

    /**
     * Create the full url for this given resource
     *
     * @param string|null $locale
     * @param array|string|null $parameters
     * @return string
     */
    public function resolveUrl(string $locale = null, $parameters = null): string;
}
