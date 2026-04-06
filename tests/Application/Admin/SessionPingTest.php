<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin;

use Thinktomorrow\Chief\Tests\ChiefTestCase;

class SessionPingTest extends ChiefTestCase
{
    public function test_admin_can_ping_session(): void
    {
        $this->asAdmin()
            ->get(route('chief.back.session.ping'))
            ->assertNoContent();

        $this->assertIsInt(session('_last_keepalive_at'));
    }
}
