<?php

namespace Thinktomorrow\Chief\Sites;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Site\Sitemap\SitemapXml;

class SitesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-sites', __DIR__.'/UI/views');

        Livewire::addNamespace(
            'chief-wire-sites',
            classNamespace: 'Thinktomorrow\\Chief\\Sites\\UI\\Livewire',
            classPath: __DIR__.'/UI/Livewire',
            classViewPath: __DIR__.'/UI/views/livewire',
        );
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
