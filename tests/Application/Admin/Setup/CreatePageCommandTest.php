<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Setup;

use Thinktomorrow\Chief\Admin\HealthMonitor\Exceptions\InvalidClassException;
use Thinktomorrow\Chief\Admin\HealthMonitor\Monitor;
use Thinktomorrow\Chief\Admin\Settings\Setting;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class CreatePageCommandTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
    }

    /** @test */
    public function it_can_create_a_page()
    {
        $this->disableExceptionHandling();
        $this->artisan('chief:page foobar');
    }
}
