<?php

namespace Thinktomorrow\Chief\Tests\Feature\HealthMonitor;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\HealthMonitor\Monitor;
use Thinktomorrow\Chief\HealthMonitor\Checks\HomepageCheck;

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
        $this->assertEquals('Het lijkt erop dat er geen homepagina ingesteld is. Stel er een in hier: <a href="http://localhost/admin/settings">Settings</a>', session('alertbarmessage'));
    }
}
