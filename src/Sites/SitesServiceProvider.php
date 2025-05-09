<?php

namespace Thinktomorrow\Chief\Sites;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Site\Sitemap\SitemapXml;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteSelect\EditSiteSelection;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteSelect\SiteSelection;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteToggle\GlobalSiteToggle;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteToggle\ModelSiteToggle;

class SitesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-sites', __DIR__.'/UI/views');

        Livewire::component('chief-wire::site-toggle', GlobalSiteToggle::class);
        Livewire::component('chief-wire::model-site-toggle', ModelSiteToggle::class);

        // Site selection for non-visitable model (model without links)
        Livewire::component('chief-wire::site-selection', SiteSelection::class);
        Livewire::component('chief-wire::edit-site-selection', EditSiteSelection::class);
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
