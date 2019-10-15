<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Fakes\MediaModule;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\Fakes\NomadicModuleManager;

class NomadicModuleTest extends TestCase
{
    use ModuleFormParams, ChiefDatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('app.fallback_locale', 'nl');

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(NomadicModuleManager::class, MediaModule::class);
    }

    /** @test */
    public function developer_can_create_nomadic_module()
    {
        $response = $this->asDeveloper()->post(route('chief.back.managers.store', 'mediamodule'), $this->validModuleParams());
        
        $this->assertCount(1, Module::all());

        $manager = app(Managers::class)->findByModel(Module::first());
        $response->assertStatus(302)->assertRedirect($manager->route('edit'));
    }

    /** @test */
    public function user_can_edit_nomadic_module()
    {
        $module = MediaModule::create([
            'slug' => 'new-slug',
        ]);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['mediamodule', $module->id]), $this->validUpdateModuleParams());

        $this->assertUpdatedModuleValues($module->fresh());
    }

    /** @test */
    public function user_cant_create_nomadic_module()
    {
        $response = $this->asAdmin()->post(route('chief.back.managers.store', 'mediamodule'), $this->validModuleParams());

        $response->assertRedirect(route('chief.back.dashboard'));
        $this->assertCount(0, Module::all());
    }

     /** @test */
     public function cant_create_more_than_one_nomadic_module()
     {
        MediaModule::create([
            'slug' => 'new-slug',
        ]);

        $response = $this->asDeveloper()->post(route('chief.back.managers.store', 'mediamodule'), $this->validModuleParams());

        $response->assertRedirect(route('chief.back.dashboard'));
        $this->assertCount(1, Module::all());
     }
}
