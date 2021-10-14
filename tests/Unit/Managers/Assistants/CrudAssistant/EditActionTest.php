<?php

namespace Thinktomorrow\Chief\Tests\Unit\Managers\Assistants\CrudAssistant;

use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Managers\Assistants\CrudAssistant;
use Thinktomorrow\Chief\Managers\Assistants\FieldsComponentAssistant;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\ManagedModelFactory;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;

class EditActionTest extends ChiefTestCase
{
    /** @test */
    public function admin_can_view_the_edit_form()
    {
        $this->disableExceptionHandling();
        $model = ManagedModelFactory::make()->fields([
            InputField::make('title'),
        ])->create(['title' => 'Original titel']);

        $manager = ManagerFactory::make()
            ->withAssistants([CrudAssistant::class, FieldsComponentAssistant::class])
            ->withModel($model)
            ->create();

        $this->asAdmin()->get($manager->route('edit', $model))
            ->assertStatus(200)
            ->assertViewIs('chief::manager.edit');
    }

    /** @test */
    public function guests_cannot_view_the_edit_form()
    {
        $model = ManagedModelFactory::make()->fields([
            InputField::make('title'),
        ])->create(['title' => 'Original titel']);

        $manager = ManagerFactory::make()
            ->withAssistants([CrudAssistant::class])
            ->withModel($model)
            ->create();

        $this->get($manager->route('edit', $model))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
