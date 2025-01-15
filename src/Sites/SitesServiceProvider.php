<?php

namespace Thinktomorrow\Chief\Sites;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Site\Sitemap\SitemapXml;
use Thinktomorrow\Chief\Sites\UI\Livewire\SitesBox;

class SitesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-sites', __DIR__ . '/UI/views');

        Livewire::component('chief-wire::resource-sites', SitesBox::class);
    }

    public function register()
    {
//        $this->app->singleton(ChiefSites::class, function () {
//            return ChiefSites::fromArray(config('chief.sites'));
//        });

        $this->app->bind(SitemapXml::class, function () {
            return new SitemapXml(new Client(['verify' => false]));
        });
    }
}
