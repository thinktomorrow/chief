<?php

namespace Thinktomorrow\Chief\Site\Visitable;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Symfony\Component\HttpFoundation\Response;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;

trait VisitableDefaults
{
    use ResolvingRoute;

    /** {@inheritdoc} */
    public function url(string $locale = null): string
    {
        if (! $locale) {
            $locale = app()->getLocale();
        }

        if (! $urlRecord = $this->urls->first(fn ($urlRecord) => $urlRecord->locale == $locale)) {
            return '';
        }

        return $this->resolveUrl($locale, [$urlRecord->slug]);
    }

    public function urls(): HasMany
    {
        return $this->hasMany(UrlRecord::class, 'model_id')
            ->where('model_type', $this->getMorphClass())
            ->whereNull('redirect_id')
        ;
    }

    public function allUrls(): HasMany
    {
        return $this->hasMany(UrlRecord::class, 'model_id')
            ->where('model_type', $this->getMorphClass())
            ;
    }

    public function isVisitable(): bool
    {
        if ($this instanceof StatefulContract && ! $this->inOnlineState()) {
            // When admin is logged in and this request is in preview mode, we allow the view
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

    /** {@inheritdoc} */
    public function baseUrlSegment(string $locale = null): string
    {
        return BaseUrlSegment::find(isset(static::$baseUrlSegment) ? (array) static::$baseUrlSegment : [], $locale);
    }

    public function response(): Response
    {
        return new \Illuminate\Http\Response(
            $this->renderView()
        );
    }
}
