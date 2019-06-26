<?php

namespace Thinktomorrow\Chief\Tests\Feature\Setup;

use Thinktomorrow\Chief\Tests\TestCase;

class SetupCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function setup_can_be_run()
    {
        $this->artisan('chief:setup');
    }
}
