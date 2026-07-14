<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Admin;

use Thinktomorrow\Chief\App\Http\Middleware\AddNoIndexHeaders;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class NoIndexHeaderTest extends ChiefTestCase
{
    public function test_open_admin_routes_send_noindex_header(): void
    {
        $this->get(route('chief.back.login'))
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_authenticated_admin_routes_send_noindex_header(): void
    {
        $this->asAdmin()
            ->get(route('chief.back.dashboard'))
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_non_html_admin_responses_send_noindex_header(): void
    {
        $this->asAdmin()
            ->get(route('chief.back.session.ping'))
            ->assertNoContent()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_public_routes_do_not_use_noindex_middleware(): void
    {
        $this->assertNotContains(AddNoIndexHeaders::class, app('router')->getRoutes()->getByName('pages.show')->gatherMiddleware());
    }
}
