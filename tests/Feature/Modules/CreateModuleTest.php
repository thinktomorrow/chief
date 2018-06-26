<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\Fakes\OtherModuleFake;
use Thinktomorrow\Chief\Tests\TestCase;

class CreateModuleTest extends TestCase
{
    use ModuleFormParams;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->app['config']->set('thinktomorrow.chief.collections.modules', [
            'newsletter' => NewsletterModuleFake::class,
            'others' => OtherModuleFake::class,
        ]);
    }

    /** @test */
    public function creating_a_new_module()
    {
        $this->disableExceptionHandling();

        $response = $this->asAdmin()
            ->post(route('chief.back.modules.store', 'newsletter'), $this->validModuleParams());

        $module = Module::ignoreCollection()->first();

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.modules.edit', $module->getKey()));

        $this->assertCount(1, Module::ignoreCollection()->get());
        $this->assertNewModuleValues($module);
    }

    /** @test */
    public function only_authenticated_admin_can_create_a_module()
    {
        $response = $this->post(route('chief.back.modules.store', 'newsletter'), $this->validModuleParams());

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(0, Module::ignoreCollection()->get());
    }

    /** @test */
    public function when_creating_module_title_is_required()
    {
        $this->assertValidation(new Module(), 'trans.nl.title', $this->validModuleParams(['trans.nl.title' => '']),
            route('chief.back.modules.index', 'newsletter'),
            route('chief.back.modules.store', 'newsletter')
        );
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $module = Module::create([
            'collection' => 'newsletter',
            'slug'   => 'foobar'
        ]);

        $this->assertCount(1, Module::ignoreCollection()->get());

        $response = $this->asAdmin()
            ->post(route('chief.back.modules.store', 'newsletter'), $this->validModuleParams([
                'slug'  => 'foobar',
                'collection' => 'newsletter',
            ])
        );

        $response->assertStatus(302);

        $modules = Module::ignoreCollection()->get();
        $this->assertCount(1, $modules);
    }
}
