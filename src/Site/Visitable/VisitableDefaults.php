<?php

namespace Thinktomorrow\Chief\Site\Visitable;

use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;
use Thinktomorrow\Chief\Site\Urls\MemoizedUrlRecord;
use Thinktomorrow\Chief\Site\Urls\UrlRecordNotFound;

trait VisitableDefaults
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

    public function isVisitable(): bool
    {
        if (public_method_exists($this, 'isPublished') && ! $this->isPublished()) {
            /** When admin is logged in and this request is in preview mode, we allow the view */
            return PreviewMode::fromRequest()->check();
        }

        return true;
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
