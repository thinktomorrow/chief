<?php

namespace Thinktomorrow\Chief\Tests\Feature\HealthMonitor;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Settings\Setting;
use Thinktomorrow\Chief\Settings\Settings;
use Thinktomorrow\Chief\HealthMonitor\Monitor;

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
        Monitor::check();
        $this->assertNotEmpty(session('alertbarmessage'));

        // Now set a homepage
        Setting::create(['key' => Setting::HOMEPAGE, 'value' => 'test']);

        $this->app->singleton(Settings::class, function ($app) { return new Settings(); });

        Monitor::check();
        $this->assertEmpty(session('alertbarmessage'));
    }
}
