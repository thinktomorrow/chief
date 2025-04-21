<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin;

use Thinktomorrow\Chief\Admin\HealthMonitor\Checks\HomepageAccessibleCheck;
use Thinktomorrow\Chief\Admin\HealthMonitor\Checks\HomepageSetCheck;
use Thinktomorrow\Chief\Admin\HealthMonitor\Exceptions\InvalidClassException;
use Thinktomorrow\Chief\Admin\HealthMonitor\Monitor;
use Thinktomorrow\Chief\Admin\Settings\Setting;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class HealthMonitorTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
    }

    public function test_checks_must_implement_healthcheck_interface()
    {
        $this->expectException(InvalidClassException::class);

        config()->set('chief.healthMonitor', [
            ArticlePage::class,
        ]);

        app(Monitor::class)->check();
    }

    public function test_the_homepagecheck_notifies_if_no_homepage_is_set()
    {
        config()->set('chief.healthMonitor', [
            HomepageSetCheck::class,
        ]);

        ArticlePage::create();

        app(Monitor::class)->check();

        $this->assertStringContainsString('Het lijkt erop dat er geen homepagina ingesteld is', session('alertbarmessage'));
    }

    public function test_the_homepagecheck_notifies_if_the_homepage_is_offline()
    {
        config()->set('chief.healthMonitor', [
            HomepageAccessibleCheck::class,
        ]);

        $model = ArticlePage::create();

        Setting::create(['key' => Setting::HOMEPAGE, 'value' => $model->modelReference()->getShort()]);

        app(Monitor::class)->check();
        $this->assertStringContainsString('Het lijkt erop dat de homepagina niet meer bereikbaar is', session('alertbarmessage'));
    }
}
