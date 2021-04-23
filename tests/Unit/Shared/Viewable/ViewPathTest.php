<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Viewable;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\ViewPath;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\NotFoundView;

class ViewPathTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['view']->addLocation(__DIR__ . '/../../../Shared/stubs/views');
    }

    /** @test */
    public function it_can_get_viewpath()
    {
        $viewPath = ViewPath::make('fake_file');

        $this->assertEquals('fake_file', $viewPath->get());
    }

    /** @test */
    public function if_basepath_is_passed_this_view_is_preferred()
    {
        $viewPath = ViewPath::make('fake_file','fake_base');

        $this->assertEquals('fake_base.fake_file', $viewPath->get());
    }

    /** @test */
    public function if_ownerpath_is_passed_this_view_is_preferred()
    {
        $viewPath = ViewPath::make('fake_file','fake_base','fake_owner');

        $this->assertEquals('fake_base.fake_owner.fake_file', $viewPath->get());
    }

    /** @test */
    public function if_view_does_not_exist_an_exception_is_thrown()
    {
        $this->expectException(NotFoundView::class);

        ViewPath::make('unknown_file')->get();
    }
}
