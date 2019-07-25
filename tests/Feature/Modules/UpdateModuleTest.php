<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Modules\ModuleManager;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\TestCase;

class UpdateModuleTest extends TestCase
{
    use ModuleFormParams;

    private $module;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(ModuleManager::class, NewsletterModuleFake::class);

        $this->module = NewsletterModuleFake::create([
            'slug' => 'new-slug',
        ]);
    }

    /** @test */
    public function admin_can_view_the_edit_form()
    {
        $this->asAdmin()->get(route('chief.back.managers.edit', ['newsletters_fake', $this->module->id]))
            ->assertViewIs('chief::back.managers.edit')
            ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_edit_form()
    {
        $this->get(route('chief.back.managers.edit', ['newslnewsletters_fakeetter', $this->module->id]))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));

        $this->assertNewModuleValues($this->module->fresh());
    }

    /** @test */
    public function it_can_edit_a_module()
    {
        $this->disableExceptionHandling();
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['newsletters_fake', $this->module->id]), $this->validUpdateModuleParams());

        $this->assertUpdatedModuleValues($this->module->fresh());
    }

    /** @test */
    public function when_updating_module_slug_is_required()
    {
        $this->assertValidation(new Module(), 'slug', $this->validUpdateModuleParams(['slug' => '']),
            route('chief.back.managers.edit', ['newsletters_fake', $this->module->id]),
            route('chief.back.managers.update', ['newsletters_fake', $this->module->id]),
            1, 'PUT'
        );
    }

    /** @test */
    public function internal_title_does_not_have_to_be_unique()
    {
        $otherModule = NewsletterModuleFake::create(['slug' => 'other-slug']);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['newsletters_fake', $this->module->id]), $this->validUpdateModuleParams([
                'slug'  => 'other-slug',
            ]));

        $this->assertEquals('other-slug', $this->module->fresh()->slug);
    }
}
