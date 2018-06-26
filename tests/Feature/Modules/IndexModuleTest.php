<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\TestCase;

class IndexModuleTest extends TestCase
{
    use ModuleFormParams;

    private $module;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->app['config']->set('thinktomorrow.chief.collections.modules', [
            'newsletter' => NewsletterModuleFake::class,
        ]);
    }

    /** @test */
    public function admin_can_view_the_modules_index()
    {
        $this->disableExceptionHandling();

        $this->asAdmin()
            ->get(route('chief.back.modules.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_modules_index()
    {
        $this->get(route('chief.back.modules.index'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
