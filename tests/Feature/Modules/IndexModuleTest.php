<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\TestCase;

class IndexModuleTest extends TestCase
{
    use ModuleFormParams;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'newsletter' => NewsletterModuleFake::class,
        ]);

        Module::create(['morph_key' => 'newsletter', 'slug' => 'new-slug']);
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
