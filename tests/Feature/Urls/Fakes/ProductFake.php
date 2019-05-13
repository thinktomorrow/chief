<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls\Fakes;

use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Urls\UrlRecord;

class ProductFake extends ManagedModelFake implements ProvidesUrl
{
    public $translationModel = ManagedModelFakeTranslation::class;
    public $translationForeignKey = 'managed_model_fake_id';

    public function url(string $locale = null): string
    {
        if(!$locale) $locale = app()->getLocale();

        return UrlRecord::findByModel($this, $locale)->slug;
    }

    public function previewUrl(string $locale = null): string
    {
        return $this->url($locale);
    }

    public static function baseUrlSegment(string $locale = null): string
    {
        return '/';
    }

    /**
     * Create the full url for this given resource
     *
     * @param string|null $locale
     * @param array|string|null $parameters
     * @return string
     */
    public function resolveUrl(string $locale = null, $parameters = null): string
    {
        $routeName = config('thinktomorrow.chief.routes.pages-show', 'pages.show');

        return route($routeName, $parameters);
    }
}