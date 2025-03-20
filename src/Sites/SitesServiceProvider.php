<?php

namespace Thinktomorrow\Chief\Sites;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Site\Sitemap\SitemapXml;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteLinks\EditSiteLinks;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteLinks\SiteLinks;
use Thinktomorrow\Chief\Sites\UI\Livewire\Sites\EditSites;
use Thinktomorrow\Chief\Sites\UI\Livewire\Sites\Sites;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteTabs;

class SitesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-sites', __DIR__.'/UI/views');

        Livewire::component('chief-wire::site-tabs', SiteTabs::class);
        Livewire::component('chief-wire::site-links', SiteLinks::class);
        Livewire::component('chief-wire::edit-site-links', EditSiteLinks::class);
        Livewire::component('chief-wire::sites', Sites::class);
        Livewire::component('chief-wire::edit-sites', EditSites::class);
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
