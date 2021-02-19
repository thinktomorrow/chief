<?php

namespace Thinktomorrow\Chief\Site\Urls\ProvidesUrl;

use Thinktomorrow\Chief\Site\Urls\MemoizedUrlRecord;
use Thinktomorrow\Chief\Site\Urls\UrlRecordNotFound;

trait ProvidingUrl
{
    use ResolvingRoute;

    /** @inheritdoc */
    public function url(string $locale = null): string
    {
        if (! $locale) {
            $locale = app()->getLocale();
        }

        try {
            $slug = MemoizedUrlRecord::findByModel($this, $locale)->slug;

            return $this->resolveUrl($locale, [$slug]);
        } catch (UrlRecordNotFound $e) {
            return '';
        }
    }

    // TODO: just used once (in url preview) so cant we just remove this?...
    public function resolveUrl(string $locale = null, $parameters = null): string
    {
        $routeName = config('chief.route.name');

        return $this->resolveRoute($routeName, $parameters, $locale);
    }

    /** @inheritdoc */
    public function baseUrlSegment(string $locale = null): string
    {
        return BaseUrlSegment::find(isset(static::$baseUrlSegment) ? (array) static::$baseUrlSegment : [], $locale);
    }
}
