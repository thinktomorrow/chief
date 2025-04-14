<?php

namespace Thinktomorrow\Chief\Sites;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Site\Sitemap\SitemapXml;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteLinks\EditSiteLinks;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteLinks\SiteLinks;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteSelect\EditSiteSelection;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteSelect\SiteSelection;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteToggle\SiteToggle;

class SitesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-sites', __DIR__.'/UI/views');

        Livewire::component('chief-wire::site-toggle', SiteToggle::class);

        // Site & link management for visitable model
        Livewire::component('chief-wire::site-links', SiteLinks::class);
        Livewire::component('chief-wire::edit-site-links', EditSiteLinks::class);

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
