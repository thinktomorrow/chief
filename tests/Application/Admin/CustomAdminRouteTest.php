<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin;

use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Tests\TestCase;

class CustomAdminRouteTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('chief.route.admin-filepath', __DIR__ . '/../../Shared/stubs/config/admin-filepaths.php');
    }

    /** @test */
    public function a_custom_admin_filepath_can_add_chief_admin_routes()
    {
        // There is a dummy.route route defined in the config test stub
        $this->assertStringEndsWith('/admin/dummy-route', route('dummy.route'));

        /** @var Route $registeredDummyRoute */
        $registeredDummyRoute = Arr::get(app('router')->getRoutes()->get('GET'), 'admin/dummy-route');

        $this->assertEquals(['web-chief','auth:chief'], $registeredDummyRoute->gatherMiddleware());
    }
}
