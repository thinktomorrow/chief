<?php

namespace Thinktomorrow\Chief\Tests\Unit\Managers\Assistants\CrudAssistant;

use Thinktomorrow\Chief\Managers\Assistants\CrudAssistant;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;

class CreateActionTest extends ChiefTestCase
{
    /** @test */
    public function admin_can_view_the_create_form()
    {
        $manager = ManagerFactory::make()->withAssistants([CrudAssistant::class])->create();

        $this->asAdmin()->get($manager->route('create'))
            ->assertStatus(200)
            ->assertViewIs('chief::back.managers.create');
    }

    /** @test */
    public function guests_cannot_view_the_create_form()
    {
        $manager = ManagerFactory::make()->withAssistants([CrudAssistant::class])->create();

        $this->get($manager->route('create'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
