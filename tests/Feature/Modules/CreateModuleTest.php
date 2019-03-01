<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Modules\ModuleManager;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\TestCase;

class CreateModuleTest extends TestCase
{
    use ModuleFormParams;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register('newsletter', ModuleManager::class, NewsletterModuleFake::class);
    }

    /** @test */
    public function creating_a_new_module()
    {
        $response = $this->asAdmin()
            ->post(route('chief.back.managers.store', 'newsletter'), $this->validModuleParams());

        $module = Module::first();

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.managers.edit', ['newsletter', $module->id]));

        $this->assertCount(1, Module::all());
        $this->assertNewModuleValues($module);
    }

    /** @test */
    public function only_authenticated_admin_can_create_a_module()
    {
        $response = $this->post(route('chief.back.managers.store', 'newsletter'), $this->validModuleParams());

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(0, Module::all());
    }

    /** @test */
    public function when_creating_module_slug_is_required()
    {
        $this->assertValidation(new Module(), 'slug', $this->validModuleParams(['slug' => '']),
            route('chief.back.managers.index', 'newsletter'),
            route('chief.back.managers.store', 'newsletter')
        );
    }

    /** @test */
    public function internal_title_is_not_required_to_be_unique()
    {
        Module::create(['slug' => 'foobar']);

        $this->assertCount(1, Module::all());

        $response = $this->asAdmin()
            ->post(route('chief.back.managers.store', 'newsletter'), $this->validModuleParams([
                'slug'  => 'foobar',
                'morph_key' => 'newsletter',
            ])
        );

        $response->assertStatus(302);

        $modules = Module::all();
        $this->assertCount(2, $modules);
    }
}
