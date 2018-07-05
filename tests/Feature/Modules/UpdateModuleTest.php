<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Modules\Application\CreateModule;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\Fakes\OtherModuleFake;
use Thinktomorrow\Chief\Tests\TestCase;

class UpdateModuleTest extends TestCase
{
    use ModuleFormParams;

    private $module;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->app['config']->set('thinktomorrow.chief.collections.modules', [
            'newsletter' => NewsletterModuleFake::class,
            'others' => OtherModuleFake::class,
        ]);

        $this->module = app(CreateModule::class)->handle('newsletter', 'new-slug');
    }

    /** @test */
    public function admin_can_view_the_edit_form()
    {
        $this->disableExceptionHandling();

        $this->asAdmin()->get(route('chief.back.modules.edit', $this->module->id))
            ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_edit_form()
    {
        $this->get(route('chief.back.modules.edit', $this->module->id))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));

        $this->assertNewModuleValues($this->module->fresh());
    }

    /** @test */
    public function it_can_edit_a_module()
    {
        $this->disableExceptionHandling();

        $this->asAdmin()
            ->put(route('chief.back.modules.update', $this->module->id), $this->validUpdateModuleParams());

        $this->assertUpdatedModuleValues($this->module->fresh());
    }

    /** @test */
    public function when_updating_module_slug_is_required()
    {
        $this->assertValidation(new Module(), 'slug', $this->validUpdateModuleParams(['slug' => '']),
            route('chief.back.modules.index'),
            route('chief.back.modules.update', $this->module->id),
            1, 'PUT'
        );
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $otherModule = Module::create(['collection' => 'newsletter', 'slug' => 'other-slug']);

        $this->assertValidation(new Module(), 'slug', $this->validUpdateModuleParams(['slug' => 'other-slug']),
            route('chief.back.modules.index'),
            route('chief.back.modules.update', $this->module->id),
            2,
            'PUT'
        );
    }
}
