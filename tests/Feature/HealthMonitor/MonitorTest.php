<?php

namespace Thinktomorrow\Chief\Tests\Feature\HealthMonitor;

use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\Settings\Setting;
use Thinktomorrow\Chief\Settings\Settings;
use Thinktomorrow\Chief\HealthMonitor\Monitor;
use Thinktomorrow\Chief\HealthMonitor\Exceptions\InvalidClassException;

class MonitorTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
    }

    /** @test */
    public function the_homepagecheck_notifies_if_no_homepage_is_set()
    {
        factory(Page::class)->create();
        app(Monitor::class)->check();
        $this->assertEquals('Het lijkt erop dat er geen homepagina ingesteld is. Stel er een in hier: <a href="http://localhost/admin/settings" class="text-secondary-800 underline hover:text-white">Settings</a>', session('alertbarmessage'));

        // Now set a homepage
        Setting::create(['key' => Setting::HOMEPAGE, 'value' => 'Thinktomorrow\Chief\Pages\Page@1']);

        $this->app->singleton(Settings::class, function ($app) { return new Settings(); });

        app(Monitor::class)->check();
        $this->assertEquals('Het lijkt erop dat de homepagina niet meer bereikbaar is. <a href="http://localhost/admin/settings" class="text-secondary-800 underline hover:text-white">Kies een nieuwe</a>.', session('alertbarmessage'));
    }

    /** @test */
    public function checks_must_implement_healthcheck_interface()
    {
        $this->expectException(InvalidClassException::class);

        config()->set('thinktomorrow.chief.healthMonitor', [
            Page::class 
        ]);

        app(Monitor::class)->check();
    }
}
